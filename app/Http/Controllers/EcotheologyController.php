<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EcotheologyContent;

class EcotheologyController extends Controller
{
    /**
     * Kiosk Display Otomatis (3-State Fullscreen Kiosk tanpa Mouse dan tanpa Scroll)
     */
    public function kiosk()
    {
        $contents = EcotheologyContent::where('is_active', true)->get();
        return view('kiosk', compact('contents'));
    }

    /**
     * Backdoor Admin Portal (Manajemen Konten Ekoteologi Islam)
     */
    public function backdoor()
    {
        $contents = EcotheologyContent::orderBy('id', 'desc')->get();
        return view('backdoor', compact('contents'));
    }

    /**
     * Tambah Konten Ekoteologi Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'theme_tag' => 'required|string|max:255',
            'waste_category' => 'required|string',
            'arabic_text' => 'required|string',
            'translation' => 'required|string',
            'reference' => 'required|string|max:255',
            'reflection' => 'required|string',
            'action' => 'required|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        EcotheologyContent::create($validated);

        return redirect()->route('backdoor')->with('success', 'Konten Ekoteologi berhasil ditambahkan ke database.');
    }

    /**
     * Update Konten Ekoteologi
     */
    public function update(Request $request, $id)
    {
        $content = EcotheologyContent::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'theme_tag' => 'required|string|max:255',
            'waste_category' => 'required|string',
            'arabic_text' => 'required|string',
            'translation' => 'required|string',
            'reference' => 'required|string|max:255',
            'reflection' => 'required|string',
            'action' => 'required|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $content->update($validated);

        return redirect()->route('backdoor')->with('success', 'Konten Ekoteologi berhasil diperbarui.');
    }

    /**
     * Hapus Konten Ekoteologi
     */
    public function destroy($id)
    {
        $content = EcotheologyContent::findOrFail($id);
        $content->delete();

        return redirect()->route('backdoor')->with('success', 'Konten Ekoteologi berhasil dihapus.');
    }

    /**
     * API: Ambil semua konten aktif untuk Kiosk
     */
    public function apiContents()
    {
        $contents = EcotheologyContent::where('is_active', true)->get();
        return response()->json($contents);
    }

    /**
     * API: Ambil 1 konten spesifik berdasarkan kategori sampah yang terdeteksi
     */
    public function apiCategoryContent(Request $request)
    {
        $category = strtolower($request->query('category', 'all'));
        
        // Cari konten yang spesifik kategori, jika tidak ada fallback ke 'all'
        $query = EcotheologyContent::where('is_active', true);
        if ($category !== 'all') {
            $matched = (clone $query)->where('waste_category', $category)->inRandomOrder()->first();
            if ($matched) {
                return response()->json($matched);
            }
        }

        $fallback = $query->inRandomOrder()->first();
        return response()->json($fallback);
    }
}

