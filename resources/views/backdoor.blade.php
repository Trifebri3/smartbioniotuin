<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Smart Waste Station - Backdoor Admin</title>

    <!-- Fonts: Inter & Amiri Quran -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #0f172a; }
        .font-quran { font-family: 'Amiri', serif; }
    </style>
</head>
<body class="min-h-screen flex flex-col" x-data="backdoorApp()">

    <!-- Top Admin Navbar (As in Image 5) -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-18 py-3 flex items-center justify-between">
            
            <!-- Left Brand -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-black text-base text-slate-900 tracking-tight">Smart Waste Station</span>
                        <span class="bg-emerald-100 text-emerald-800 text-[10px] font-extrabold px-2 py-0.5 rounded-md uppercase tracking-wider border border-emerald-300">
                            BACKDOOR ADMIN
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 font-medium">Portal Manajemen Konten Ekoteologi Islam &bull; UIN Sunan Gunung Djati Bandung</p>
                </div>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-2.5">
                <a href="/kiosk" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 transition">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span>Layar Kiosk</span>
                </a>
                <a href="/auto-sort" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 transition">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                    <span>Kamera Deteksi</span>
                </a>
                <button @click="openAddModal()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>+ Tambah Konten</span>
                </button>
            </div>

        </div>
    </header>

    <!-- Main Content Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-6 flex-1 w-full space-y-5">
        
        <!-- Flash Alert -->
        @if(session('success'))
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <span class="text-emerald-500 text-xs">Berhasil</span>
            </div>
        @endif

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-black text-slate-900">Koleksi Ayat, Hadits &amp; Nilai Ekoteologi</h2>
                <p class="text-xs text-slate-500">Konten ini otomatis tampil bergantian pada layar Kiosk dan setelah deteksi jenis sampah.</p>
            </div>
            <span class="text-xs font-mono text-slate-500 font-semibold">{{ count($contents) }} Konten Terdaftar</span>
        </div>

        <!-- Grid of Cards (Matching Image 5) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($contents as $item)
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
                    
                    <div class="space-y-3">
                        <!-- Card Header -->
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 block">
                                    {{ $item->theme_tag }}
                                </span>
                                <h3 class="font-black text-sm text-slate-900 mt-0.5">{{ $item->title }}</h3>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600 border border-slate-200 uppercase">
                                {{ $item->waste_category }}
                            </span>
                        </div>

                        <!-- Arabic Box -->
                        <div class="bg-emerald-50/70 border border-emerald-100 rounded-xl p-3 text-right">
                            <p class="font-quran text-lg text-emerald-950 font-bold leading-relaxed" dir="rtl">
                                {{ $item->arabic_text }}
                            </p>
                        </div>

                        <!-- Translation -->
                        <p class="text-xs text-slate-600 italic leading-relaxed">
                            "{{ $item->translation }}"
                        </p>
                        <div class="text-[11px] font-bold text-emerald-800">
                            {{ $item->reference }}
                        </div>

                        <!-- Refleksi & Aksi -->
                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 text-xs space-y-2">
                            <div>
                                <span class="font-extrabold text-[10px] text-emerald-700 uppercase tracking-wide block">
                                    REFLEKSI &amp; MASA KINI:
                                </span>
                                <p class="text-slate-600 mt-0.5 text-[11px] leading-relaxed">
                                    {{ $item->reflection }}
                                </p>
                            </div>
                            <div class="pt-1 border-t border-slate-200/60">
                                <span class="font-extrabold text-[10px] text-amber-700 uppercase tracking-wide block">
                                    TINDAKAN / AKSI NYATA:
                                </span>
                                <p class="text-slate-600 mt-0.5 text-[11px] leading-relaxed">
                                    {{ $item->action }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer Actions (Pratinjau, Edit, Hapus) -->
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                        <button @click="previewContent({{ json_encode($item) }})" class="font-semibold text-slate-600 hover:text-emerald-700 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <span>Pratinjau</span>
                        </button>
                        
                        <div class="flex items-center gap-2">
                            <button @click="editContent({{ json_encode($item) }})" class="font-semibold text-blue-600 hover:text-blue-800">
                                Edit
                            </button>
                            <form action="{{ route('backdoor.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus konten ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-semibold text-red-500 hover:text-red-700">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

    </main>

    <!-- Modal Form (Tambah / Edit Konten) -->
    <div x-show="isModalOpen" x-transition class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
        <div @click.away="isModalOpen = false" class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="font-black text-base text-slate-900" x-text="isEditMode ? 'Edit Konten Ekoteologi' : 'Tambah Konten Ekoteologi Baru'"></h3>
                <button @click="isModalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
            </div>

            <form :action="isEditMode ? '/backdoor/update/' + form.id : '{{ route('backdoor.store') }}'" method="POST" class="space-y-3">
                @csrf
                <template x-if="isEditMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Judul Konten</label>
                        <input type="text" name="title" x-model="form.title" required class="w-full text-xs p-2.5 border rounded-xl" placeholder="Contoh: Larangan Merusak Tatanan Bumi">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tema / Tagar</label>
                        <input type="text" name="theme_tag" x-model="form.theme_tag" required class="w-full text-xs p-2.5 border rounded-xl" placeholder="Contoh: Kelestarian Alam • Al-Qur'an">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Sampah Terkait</label>
                        <select name="waste_category" x-model="form.waste_category" class="w-full text-xs p-2.5 border rounded-xl">
                            <option value="all">Semua Kategori (Umum)</option>
                            <option value="organik">Organik (BIN 1)</option>
                            <option value="plastik">Anorganik / Plastik (BIN 2)</option>
                            <option value="kertas">Kertas &amp; Karton (BIN 3)</option>
                            <option value="logam">Logam &amp; Kaca (BIN 4)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Rujukan (Surat/Hadits)</label>
                        <input type="text" name="reference" x-model="form.reference" required class="w-full text-xs p-2.5 border rounded-xl" placeholder="Contoh: QS. Al-A'raf: 56">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Teks Arab (Berharakat)</label>
                    <textarea name="arabic_text" x-model="form.arabic_text" rows="2" dir="rtl" required class="font-quran w-full text-base p-2.5 border rounded-xl leading-relaxed" placeholder="Teks Arab ayat/hadits..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Terjemahan Bahasa Indonesia</label>
                    <textarea name="translation" x-model="form.translation" rows="2" required class="w-full text-xs p-2.5 border rounded-xl leading-relaxed" placeholder="Terjemahan resmi..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Refleksi &amp; Masa Kini</label>
                    <textarea name="reflection" x-model="form.reflection" rows="3" required class="w-full text-xs p-2.5 border rounded-xl leading-relaxed" placeholder="Ulasan refleksi kontekstual terhadap gaya hidup modern dan krisis lingkungan..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tindakan / Aksi Nyata</label>
                    <textarea name="action" x-model="form.action" rows="2" required class="w-full text-xs p-2.5 border rounded-xl leading-relaxed" placeholder="Langkah nyata apa yang harus dilakukan pengguna saat ini..."></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t">
                    <button type="button" @click="isModalOpen = false" class="px-4 py-2 border rounded-xl text-xs font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm" x-text="isEditMode ? 'Simpan Perubahan' : 'Tambah Konten'"></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Pratinjau Tampilan Kiosk -->
    <div x-show="isPreviewOpen" x-transition class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-md flex items-center justify-center p-4" style="display: none;">
        <div @click.away="isPreviewOpen = false" class="bg-slate-900 border border-emerald-500/50 rounded-3xl max-w-3xl w-full p-6 text-white shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-emerald-900/80 pb-3">
                <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Pratinjau Layar Ekoteologi Kiosk</span>
                <button @click="isPreviewOpen = false" class="text-slate-400 hover:text-white text-lg font-bold">&times;</button>
            </div>

            <div class="space-y-4 py-2">
                <div class="text-center">
                    <span class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider block" x-text="previewData.theme_tag"></span>
                    <h3 class="text-xl sm:text-2xl font-black text-white mt-1" x-text="previewData.title"></h3>
                </div>

                <div class="bg-emerald-950/70 border border-emerald-500/40 rounded-2xl p-4 text-right">
                    <p class="font-quran text-xl text-emerald-300 font-bold leading-relaxed" dir="rtl" x-text="previewData.arabic_text"></p>
                    <p class="text-xs text-slate-200 italic mt-2 text-left" x-text="'\"' + previewData.translation + '\"'"></p>
                    <div class="text-right text-[11px] font-bold text-emerald-400 mt-1" x-text="'— ' + previewData.reference"></div>
                </div>

                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="bg-white/5 border border-white/10 p-3 rounded-xl">
                        <span class="text-emerald-400 font-bold block mb-1">Refleksi &amp; Masa Kini:</span>
                        <p class="text-slate-300 leading-relaxed text-[11px]" x-text="previewData.reflection"></p>
                    </div>
                    <div class="bg-white/5 border border-white/10 p-3 rounded-xl">
                        <span class="text-amber-400 font-bold block mb-1">Tindakan / Aksi Nyata:</span>
                        <p class="text-slate-300 leading-relaxed text-[11px]" x-text="previewData.action"></p>
                    </div>
                </div>
            </div>

            <div class="text-right pt-2 border-t border-emerald-900/80">
                <button @click="isPreviewOpen = false" class="px-4 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-bold">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Alpine App Controller -->
    <script>
        function backdoorApp() {
            return {
                isModalOpen: false,
                isEditMode: false,
                isPreviewOpen: false,
                previewData: {},
                form: {
                    id: null,
                    title: '',
                    theme_tag: '',
                    waste_category: 'all',
                    reference: '',
                    arabic_text: '',
                    translation: '',
                    reflection: '',
                    action: ''
                },
                openAddModal() {
                    this.isEditMode = false;
                    this.form = {
                        id: null,
                        title: '',
                        theme_tag: 'Kelestarian Alam • Al-Qur\'an',
                        waste_category: 'all',
                        reference: '',
                        arabic_text: '',
                        translation: '',
                        reflection: '',
                        action: ''
                    };
                    this.isModalOpen = true;
                },
                editContent(item) {
                    this.isEditMode = true;
                    this.form = Object.assign({}, item);
                    this.isModalOpen = true;
                },
                previewContent(item) {
                    this.previewData = Object.assign({}, item);
                    this.isPreviewOpen = true;
                }
            }
        }
    </script>
</body>
</html>
