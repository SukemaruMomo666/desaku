@extends('layouts.app')

@section('header_title', 'Dashboard Administrator')

@section('content')
<div class="space-y-6">

    <!-- Welcome Card -->
    <div class="bg-gradient-to-br from-primary-600 to-primary-800 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-primary-500/20 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-10 -mb-10 w-40 h-40 bg-black opacity-10 rounded-full blur-2xl"></div>
        
        <div class="relative z-10">
            <h2 class="text-3xl font-extrabold tracking-tight mb-2">Selamat Datang, Admin! 👑</h2>
            <p class="text-primary-100 max-w-2xl text-lg">
                Pantau seluruh permohonan surat warga, kelola master data, dan pastikan pelayanan kelurahan berjalan dengan cepat dan transparan.
            </p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat 1 -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-start gap-4 transition-transform hover:-translate-y-1">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 mb-1">Total Warga</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_warga']) }}</h3>
            </div>
        </div>

        <!-- Stat 2 -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-start gap-4 transition-transform hover:-translate-y-1">
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 mb-1">Surat Menunggu</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_surat_menunggu']) }}</h3>
            </div>
        </div>

        <!-- Stat 3 -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-start gap-4 transition-transform hover:-translate-y-1">
            <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 mb-1">Surat Selesai</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_surat_selesai']) }}</h3>
            </div>
        </div>

        <!-- Stat 4 -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-start gap-4 transition-transform hover:-translate-y-1">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 mb-1">Master Surat</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_jenis_surat']) }}</h3>
            </div>
        </div>
    </div>

    <!-- Latest Requests -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mt-8">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-lg text-gray-900">Permohonan Terbaru</h3>
            <a href="#" class="text-sm font-semibold text-primary-600 hover:text-primary-700">Lihat Semua &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50/50 text-gray-500">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Pemohon</th>
                        <th class="px-6 py-4 font-semibold">Jenis Surat</th>
                        <th class="px-6 py-4 font-semibold">Waktu Pengajuan</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($latestRequests as $req)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $req->user->name }}</div>
                                <div class="text-xs text-gray-500">NIK: {{ $req->user->nik }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-700">{{ $req->letterType->code }}</div>
                                <div class="text-xs text-gray-500">{{ Str::limit($req->letterType->name, 30) }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $req->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4">
                                @if($req->status === 'menunggu')
                                    <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-bold bg-orange-100 text-orange-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> Menunggu
                                    </span>
                                @elseif($req->status === 'diproses')
                                    <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Diproses
                                    </span>
                                @elseif($req->status === 'siap_diambil')
                                    <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Siap Diambil
                                    </span>
                                @elseif($req->status === 'selesai')
                                    <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span> Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.letter-requests.show', $req->id) }}" class="inline-flex items-center justify-center py-2 px-4 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs transition-colors">
                                    Tinjau
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 text-gray-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <p class="text-gray-500 font-medium">Belum ada permohonan surat masuk.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
