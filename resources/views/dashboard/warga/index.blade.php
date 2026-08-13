@extends('layouts.app')

@section('header_title', 'Beranda Warga')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    
    <!-- Welcome Banner -->
    <div class="bg-primary-600 rounded-3xl p-6 sm:p-10 text-white shadow-xl shadow-primary-500/20 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
        <div class="relative z-10">
            <h2 class="text-3xl font-extrabold mb-2 tracking-tight">Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h2>
            <p class="text-primary-100 text-lg max-w-2xl">Selamat datang di portal layanan administrasi desa. Apa yang ingin Anda urus hari ini?</p>
            
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('citizen.request.create') }}" class="bg-white text-primary-700 px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-gray-50 transition-colors flex items-center gap-2 hover:-translate-y-0.5 duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Ajukan Surat Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Stat Card 1 -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Diproses Admin</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $requests->where('status', 'diproses')->count() + $requests->where('status', 'menunggu')->count() }}</h3>
            </div>
        </div>
        
        <!-- Stat Card 2 -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-accent/10 text-accent flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Siap Diambil</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $requests->where('status', 'selesai')->count() }}</h3>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Pengajuan Selesai</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $requests->where('status', 'selesai')->count() }}</h3>
            </div>
        </div>
    </div>

    <!-- Recent Requests -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900">Pengajuan Terakhir Anda</h3>
            <button class="text-sm font-semibold text-primary-600 hover:text-primary-700">Lihat Semua</button>
        </div>
        
        @if($requests->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($requests->take(5) as $req)
                    <div class="p-6 hover:bg-gray-50 transition-colors flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center shrink-0 mt-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $req->letterType->name }}</h4>
                                <div class="flex flex-wrap items-center gap-3 mt-1 text-sm text-gray-500">
                                    <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> {{ $req->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
                            @php
                                $statusColors = [
                                    'menunggu' => 'bg-yellow-100 text-yellow-700',
                                    'diproses' => 'bg-blue-100 text-blue-700',
                                    'selesai' => 'bg-green-100 text-green-700',
                                    'ditolak' => 'bg-red-100 text-red-700',
                                ];
                                $color = $statusColors[$req->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $color }}">
                                {{ $req->status }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold mb-1">Belum ada pengajuan</h4>
                <p class="text-gray-500 text-sm">Anda belum mengajukan surat apapun. Mulai ajukan sekarang.</p>
            </div>
        @endif
    </div>
</div>
@endsection
