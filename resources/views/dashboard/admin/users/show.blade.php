@extends('layouts.app')

@section('header_title', 'Detail & Histori Warga')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-br from-primary-600 to-primary-800"></div>
                
                <div class="relative z-10">
                    <div class="w-24 h-24 mx-auto rounded-full bg-white p-1 shadow-lg mb-4 mt-8">
                        <div class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center text-3xl font-bold text-gray-400">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $user->name }}</h3>
                    <p class="text-sm font-mono text-gray-500 mb-6">{{ $user->nik }}</p>
                    
                    <div class="space-y-4 text-left">
                        <div class="flex items-center gap-3 p-4 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-gray-500">Nomor WhatsApp</div>
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', $user->phone) }}" target="_blank" class="font-medium text-gray-900 hover:text-green-600 transition-colors">{{ $user->phone }}</a>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-4 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-gray-500">Terdaftar Sejak</div>
                                <div class="font-medium text-gray-900">{{ $user->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden h-full">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="font-bold text-lg text-gray-900">Riwayat Pengajuan Surat ({{ $user->letterRequests->count() }})</h3>
                </div>
                
                <div class="p-6">
                    <div class="space-y-6">
                        @forelse($user->letterRequests as $req)
                            <div class="relative pl-8 sm:pl-32 py-2 group">
                                <!-- Timeline Line -->
                                <div class="absolute left-4 sm:left-28 top-0 bottom-0 w-px bg-gray-200 group-last:bg-transparent"></div>
                                
                                <!-- Timeline Dot -->
                                <div class="absolute left-2 sm:left-26 w-5 h-5 rounded-full border-4 border-white shadow-sm {{ $req->status === 'selesai' ? 'bg-green-500' : ($req->status === 'ditolak' ? 'bg-red-500' : 'bg-primary-500') }} mt-1"></div>
                                
                                <!-- Date (Desktop) -->
                                <div class="hidden sm:block absolute left-0 top-3 text-right w-20 text-xs font-bold text-gray-500">
                                    {{ $req->created_at->format('d M Y') }}
                                </div>
                                
                                <!-- Card -->
                                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 hover:border-primary-200 transition-colors">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                                        <div class="font-bold text-gray-900 text-lg">{{ $req->letterType->name }}</div>
                                        <div>
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
                                        </div>
                                    </div>
                                    <div class="text-sm text-gray-500 mb-4 sm:hidden">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $req->created_at->format('d M Y, H:i') }}
                                    </div>
                                    <div class="flex justify-end">
                                        <a href="{{ route('admin.letter-requests.show', $req->id) }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700">Lihat Detail Pengajuan &rarr;</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 text-gray-300 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <p class="text-gray-500 font-medium">Warga ini belum pernah mengajukan permohonan surat.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
