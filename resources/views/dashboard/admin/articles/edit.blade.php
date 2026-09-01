@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center gap-4 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <a href="{{ route('admin.articles.index') }}" class="p-2 hover:bg-gray-50 rounded-xl transition-colors">
            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Konten</h1>
            <p class="text-gray-500 text-sm mt-1">Perbarui informasi, berita, atau pengumuman yang sudah ada.</p>
        </div>
    </div>

    <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 space-y-8">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Judul -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Judul Konten <span class="text-red-500">*</span></label>
                <input type="text" name="title" required value="{{ old('title', $article->title) }}" 
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Tipe -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Konten <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select name="type" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all appearance-none bg-white">
                        <option value="Informasi" {{ old('type', $article->type) == 'Informasi' ? 'selected' : '' }}>Informasi</option>
                        <option value="Berita" {{ old('type', $article->type) == 'Berita' ? 'selected' : '' }}>Berita</option>
                        <option value="Kegiatan" {{ old('type', $article->type) == 'Kegiatan' ? 'selected' : '' }}>Kegiatan / Acara</option>
                        <option value="Pengumuman" {{ old('type', $article->type) == 'Pengumuman' ? 'selected' : '' }}>Pengumuman Penting</option>
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
                <textarea name="content" rows="6" 
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all">{{ old('content', $article->content) }}</textarea>
                @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Gambar -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Gambar / Poster</label>
                
                @if($article->image)
                <div class="mb-4 bg-gray-50 p-2 rounded-xl border border-gray-100 inline-block">
                    <img src="{{ Storage::url($article->image) }}" alt="Current Image" class="h-32 rounded-lg object-cover">
                    <p class="text-xs text-gray-500 mt-2 text-center">Gambar saat ini</p>
                </div>
                @endif
                
                <div class="relative">
                    <input type="file" name="image" accept="image/*" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                </div>
                <p class="text-gray-400 text-xs mt-1">Kosongkan jika tidak ingin mengubah gambar.</p>
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Aktif -->
            <label class="flex items-center gap-3 p-4 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:border-primary-500 transition-colors">
                <input type="checkbox" name="is_active" value="1" class="w-5 h-5 text-primary-600 rounded border-gray-300 focus:ring-primary-500" {{ old('is_active', $article->is_active) ? 'checked' : '' }}>
                <div>
                    <span class="font-bold text-gray-900 block">Status Aktif</span>
                    <span class="text-sm text-gray-500">Centang agar tampil di halaman utama, hilangkan untuk menyembunyikan.</span>
                </div>
            </label>
        </div>

        <div class="flex justify-end pt-6 border-t border-gray-100">
            <button type="submit" class="px-8 py-3 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-500/30">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
