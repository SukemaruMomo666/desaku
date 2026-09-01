@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Papan Informasi & Kegiatan</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola konten informasi, berita, atau kegiatan yang tampil di halaman utama.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.articles.create') }}" class="px-6 py-2.5 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all flex items-center gap-2 shadow-lg shadow-primary-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Konten
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-start gap-3">
        <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <p class="font-medium">{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($articles as $article)
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-lg transition-all">
            @if($article->image)
            <div class="h-48 w-full overflow-hidden bg-gray-100">
                <img src="{{ Storage::url($article->image) }}" alt="Gambar" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
            </div>
            @else
            <div class="h-32 w-full bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center">
                <svg class="w-12 h-12 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            </div>
            @endif
            
            <div class="p-5 flex-1 flex flex-col">
                <div class="flex items-center justify-between mb-3">
                    <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold uppercase tracking-wider rounded-lg">{{ $article->type }}</span>
                    <form action="{{ route('admin.articles.toggle', $article->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs font-bold px-3 py-1 rounded-lg transition-colors {{ $article->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                            {{ $article->is_active ? 'AKTIF' : 'SEMBUNYI' }}
                        </button>
                    </form>
                </div>
                
                <h3 class="font-bold text-gray-900 text-lg mb-2 line-clamp-2">{{ $article->title }}</h3>
                <p class="text-gray-500 text-sm line-clamp-3 mb-4 flex-1">{{ strip_tags($article->content) }}</p>
                
                <div class="flex items-center gap-2 pt-4 border-t border-gray-100 mt-auto">
                    <a href="{{ route('admin.articles.edit', $article->id) }}" class="flex-1 text-center py-2 bg-blue-50 text-blue-600 font-medium rounded-xl hover:bg-blue-100 transition-colors">
                        Edit
                    </a>
                    <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus informasi ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-center py-2 bg-red-50 text-red-600 font-medium rounded-xl hover:bg-red-100 transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm flex flex-col items-center justify-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Belum ada konten</h3>
            <p class="text-gray-500 mb-6 max-w-sm">Anda belum menambahkan informasi, pengumuman, atau kegiatan apapun.</p>
            <a href="{{ route('admin.articles.create') }}" class="px-6 py-2.5 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-500/30">
                Tambah Konten Pertama
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection
