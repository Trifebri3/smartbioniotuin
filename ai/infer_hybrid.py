"""
Script inferensi sederhana buat model Hybrid CNN-XGBoost.
Beda sama CNN Baseline, ini butuh 2 file model sekaligus:
  1. feature_extractor_<varian>.tflite  (bagian CNN, hasilin embedding 1280 dimensi)
  2. xgb_classifier.json                (classifier XGBoost, format native, BUKAN .tflite)

Cara pakai:
    pip install tflite-runtime xgboost pillow numpy --break-system-packages
    (kalau tflite-runtime gagal/gak kompatibel, install tensorflow biasa aja,
     script ini otomatis fallback)

    python infer_hybrid.py foto_sampah.jpg
    python infer_hybrid.py foto_sampah.jpg --feature_extractor feature_extractor_int8.tflite --xgb xgb_classifier.json
"""

import argparse
import os
import sys
import numpy as np
from PIL import Image

try:
    import xgboost as xgb
except ImportError:
    xgb = None

try:
    from tflite_runtime.interpreter import Interpreter
except ImportError:
    try:
        from ai_edge_litert.interpreter import Interpreter
    except ImportError:
        try:
            from tensorflow.lite import Interpreter
        except ImportError:
            Interpreter = None

CLASSES = ["organik", "plastik", "kertas", "logam_kaca"]
IMG_SIZE = 224


def resolve_path(path, candidates):
    """Mencari path file model yang valid: di path langsung, di models/, atau root."""
    if path and os.path.exists(path):
        return path
    base_dir = os.path.dirname(os.path.abspath(__file__))
    if path:
        in_models = os.path.join(base_dir, "models", path)
        if os.path.exists(in_models):
            return in_models
    for cand in candidates:
        cand_in_models = os.path.join(base_dir, "models", cand)
        if os.path.exists(cand_in_models):
            return cand_in_models
        cand_in_root = os.path.join(base_dir, cand)
        if os.path.exists(cand_in_root):
            return cand_in_root
    return path


def load_and_preprocess(path):
    """Sama persis kayak preprocessing training: resize 224x224 + normalisasi
    setara mobilenet_v2.preprocess_input (rentang [-1, 1])."""
    img = Image.open(path).convert("RGB").resize((IMG_SIZE, IMG_SIZE))
    arr = np.array(img, dtype=np.float32)
    arr = arr / 127.5 - 1.0
    return np.expand_dims(arr, axis=0)


def extract_embedding(feature_extractor_path, image_path):
    """Tahap 1: gambar -> embedding 1280 dimensi lewat feature extractor TFLite."""
    if Interpreter is None:
        print("Error: Library TFLite interpreter belum terinstall.")
        print("Silakan jalankan: pip install tflite-runtime (atau pip install tensorflow)")
        sys.exit(1)

    interpreter = Interpreter(model_path=feature_extractor_path)
    interpreter.allocate_tensors()
    input_details = interpreter.get_input_details()
    output_details = interpreter.get_output_details()

    img = load_and_preprocess(image_path)
    is_int8 = input_details[0]["dtype"] == np.int8

    if is_int8:
        scale, zero_point = input_details[0]["quantization"]
        img = (img / scale + zero_point).astype(np.int8)
    else:
        img = img.astype(np.float32)

    interpreter.set_tensor(input_details[0]["index"], img)
    interpreter.invoke()
    embedding = interpreter.get_tensor(output_details[0]["index"])

    if output_details[0]["dtype"] == np.int8:
        scale, zero_point = output_details[0]["quantization"]
        embedding = (embedding.astype(np.float32) - zero_point) * scale

    return embedding


def main():
    parser = argparse.ArgumentParser(description="Inferensi Hybrid CNN-XGBoost")
    parser.add_argument("image", help="Path ke file gambar yang mau ditest")
    parser.add_argument(
        "--feature_extractor",
        default="feature_extractor_int8.tflite",
        help="Path ke file .tflite feature extractor (default: feature_extractor_int8.tflite)",
    )
    parser.add_argument(
        "--xgb",
        default="xgb_classifier.json",
        help="Path ke file xgb_classifier.json (default: xgb_classifier.json)",
    )
    parser.add_argument(
        "--json",
        action="store_true",
        help="Keluarkan hasil dalam format JSON murni untuk integrasi API",
    )
    args = parser.parse_args()

    if not os.path.exists(args.image):
        if args.json:
            import json
            print(json.dumps({"status": "error", "message": f"File gambar '{args.image}' tidak ditemukan!"}))
            sys.exit(1)
        print(f"Error: File gambar '{args.image}' tidak ditemukan!")
        sys.exit(1)

    fe_path = resolve_path(
        args.feature_extractor,
        ["feature_extractor_int8.tflite", "feature_extractor_float32.tflite"]
    )
    xgb_path = resolve_path(
        args.xgb,
        ["xgb_classifier.json"]
    )

    if not os.path.exists(fe_path):
        if args.json:
            import json
            print(json.dumps({"status": "error", "message": "Feature extractor model missing"}))
            sys.exit(1)
        print(f"Error: File feature extractor '{args.feature_extractor}' tidak ditemukan di '{fe_path}'!")
        sys.exit(1)

    if not os.path.exists(xgb_path):
        if args.json:
            import json
            print(json.dumps({"status": "error", "message": "XGBoost model missing"}))
            sys.exit(1)
        print(f"Error: File XGBoost classifier '{args.xgb}' tidak ditemukan di '{xgb_path}'!")
        sys.exit(1)

    # Tahap 1: CNN feature extractor -> embedding
    embedding = extract_embedding(fe_path, args.image)

    # Tahap 2: embedding -> XGBoost classifier
    if xgb is None:
        if args.json:
            import json
            print(json.dumps({"status": "error", "message": "Library xgboost belum terinstall"}))
            sys.exit(1)
        print("Error: Library 'xgboost' belum terinstall.")
        print("Silakan jalankan: pip install xgboost")
        sys.exit(1)

    xgb_model = xgb.XGBClassifier()
    xgb_model.load_model(xgb_path)
    probs = xgb_model.predict_proba(embedding)[0]

    pred_idx = int(np.argmax(probs))

    if args.json:
        import json
        result = {
            "status": "success",
            "class": CLASSES[pred_idx],
            "confidence": round(float(probs[pred_idx] * 100.0), 2),
            "probs": {cls: round(float(p), 4) for cls, p in zip(CLASSES, probs)},
        }
        print(json.dumps(result))
        return

    print(f"\nFeature extractor : {fe_path}")
    print(f"XGBoost classifier: {xgb_path}")
    print(f"Gambar            : {args.image}\n")
    print("=== Hasil Prediksi ===")
    for cls, p in sorted(zip(CLASSES, probs), key=lambda x: -x[1]):
        marker = "  <-- prediksi" if cls == CLASSES[pred_idx] else ""
        print(f"  {cls:12s}: {p * 100:6.2f}%{marker}")
    print(f"\nKesimpulan: {CLASSES[pred_idx]} ({probs[pred_idx] * 100:.2f}% yakin)")


if __name__ == "__main__":
    main()
