@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center gap-4 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <a href="{{ route('admin.articles.index') }}" class="p-2 hover:bg-gray-50 rounded-xl transition-colors">
            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Konten Baru</h1>
            <p class="text-gray-500 text-sm mt-1">Buat informasi, berita, atau pengumuman baru untuk warga.</p>
        </div>
    </div>

    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 space-y-8">
        @csrf

        <div class="space-y-6">
            <!-- Judul -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Judul Konten <span class="text-red-500">*</span></label>
                <input type="text" name="title" required value="{{ old('title') }}" 
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all"
                    placeholder="Contoh: Jadwal Posyandu Bulan September">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Tipe -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Konten <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select name="type" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all appearance-none bg-white">
                        <option value="Informasi" {{ old('type') == 'Informasi' ? 'selected' : '' }}>Informasi</option>
                        <option value="Berita" {{ old('type') == 'Berita' ? 'selected' : '' }}>Berita</option>
                        <option value="Kegiatan" {{ old('type') == 'Kegiatan' ? 'selected' : '' }}>Kegiatan / Acara</option>
                        <option value="Pengumuman" {{ old('type') == 'Pengumuman' ? 'selected' : '' }}>Pengumuman Penting</option>
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Konten -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Isi Konten</label>
                
                <!-- Trix Editor Dependencies -->
                <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
                <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
                <style>
                    trix-toolbar [data-trix-button-group="file-tools"] { display: none; }
                    .trix-content { min-height: 250px; }
                    /* Fix trix active button background to match our theme */
                    trix-toolbar .trix-button.trix-active { background: #e0e7ff; }
                </style>

                <input id="article_content" type="hidden" name="content" value="{{ old('content') }}">
                <trix-editor input="article_content" class="trix-content w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all bg-white" placeholder="Tuliskan berita lengkap di sini (mendukung format tebal, miring, poin, dll)..."></trix-editor>
                
                @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Gambar -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Gambar / Poster</label>
                <div class="relative">
                    <input type="file" name="image" accept="image/*" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                </div>
                <p class="text-gray-400 text-xs mt-1">Format: JPG, PNG. Maksimal ukuran 5MB. Opsional.</p>
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Aktif -->
            <label class="flex items-center gap-3 p-4 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:border-primary-500 transition-colors">
                <input type="checkbox" name="is_active" value="1" class="w-5 h-5 text-primary-600 rounded border-gray-300 focus:ring-primary-500" {{ old('is_active', true) ? 'checked' : '' }}>
                <div>
                    <span class="font-bold text-gray-900 block">Langsung Tampilkan (Aktif)</span>
                    <span class="text-sm text-gray-500">Jika dicentang, konten ini akan langsung terlihat oleh warga di halaman depan.</span>
                </div>
            </label>
        </div>

        <div class="flex justify-end pt-6 border-t border-gray-100">
            <button type="submit" class="px-8 py-3 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-500/30">
                Simpan & Terbitkan Konten
            </button>
        </div>
    </form>
</div>
@endsection
