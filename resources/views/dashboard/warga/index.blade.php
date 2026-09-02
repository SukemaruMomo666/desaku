@extends('layouts.app')

@section('header_title', 'Beranda Warga')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    
    <!-- Welcome Banner -->
    <div class="bg-primary-600 rounded-3xl p-6 sm:p-10 text-white shadow-xl shadow-primary-500/20 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
        <div class="relative z-10">
            <h2 class="text-3xl font-extrabold mb-2 tracking-tight">Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h2>
            <p class="text-primary-100 text-lg max-w-2xl">Selamat datang di portal layanan administrasi kelurahan. Apa yang ingin Anda urus hari ini?</p>
            
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
            <a href="{{ route('citizen.history') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700">Lihat Semua</a>
        </div>
        
        @if($requests->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($requests->take(5) as $req)
                    <div x-data="{ showModal: false }" class="p-6 hover:bg-gray-50 transition-colors flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
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
                        <div class="flex flex-row items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
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
                            <button @click="showModal = true" class="text-sm font-semibold text-primary-600 hover:text-primary-700 bg-primary-50 px-3 py-1.5 rounded-lg transition-colors">
                                Lihat Detail
                            </button>
                        </div>
                        
                        <!-- Modal Detail -->
                        <template x-teleport="body">
                            <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <!-- Backdrop -->
                                <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-secondary-900/60 backdrop-blur-sm transition-opacity" @click="showModal = false" aria-hidden="true"></div>
                                
                                <!-- Modal Panel -->
                                <div x-show="showModal" 
                                     x-transition:enter="ease-out duration-300"
                                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                     x-transition:leave="ease-in duration-200"
                                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                     class="relative z-10 bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-2xl w-full flex flex-col max-h-[90vh] border border-gray-100">
                                    
                                    <!-- Modal Header -->
                                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex justify-between items-center shrink-0">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-2xl bg-primary-50 flex items-center justify-center text-primary-600 shadow-inner">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <div>
                                                <h3 class="text-xl font-extrabold text-gray-900" id="modal-title">Detail Pengajuan Surat</h3>
                                                <p class="text-sm text-gray-500 font-medium mt-0.5">Lihat informasi lengkap dari permohonan Anda</p>
                                            </div>
                                        </div>
                                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-xl transition-colors focus:outline-none">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>

                                    <!-- Modal Body (Scrollable) -->
                                    <div class="px-6 py-6 overflow-y-auto flex-1 bg-white">
                                        <div class="space-y-6">
                                            
                                            <!-- Overview Cards -->
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <!-- Jenis Surat -->
                                                <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Jenis Surat</p>
                                                    <p class="font-bold text-gray-900">{{ $req->letterType->name }}</p>
                                                </div>
                                                <!-- Tanggal & Status -->
                                                <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100 flex flex-col justify-center">
                                                    <div class="flex justify-between items-center w-full">
                                                        <div>
                                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Diajukan Pada</p>
                                                            <p class="font-bold text-gray-900">{{ $req->created_at->format('d M Y, H:i') }}</p>
                                                        </div>
                                                        <div class="text-right">
                                                            <span class="px-3 py-1.5 rounded-xl text-xs font-extrabold uppercase tracking-wider shadow-sm {{ $color }}">
                                                                {{ $req->status }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($req->admin_notes)
                                                <!-- Admin Notes -->
                                                <div class="bg-red-50 p-5 rounded-2xl border border-red-100 flex gap-4 items-start shadow-sm">
                                                    <div class="bg-red-100 p-2 rounded-full text-red-600 shrink-0">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-bold text-red-800">Catatan dari Admin</h4>
                                                        <p class="text-sm text-red-700 mt-1 font-medium">{{ $req->admin_notes }}</p>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($req->submitted_data && count($req->submitted_data) > 0)
                                            <!-- Data Isian Form -->
                                            <div>
                                                <h4 class="text-base font-bold text-gray-900 flex items-center gap-2 mb-4">
                                                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    Data Isian Formulir
                                                </h4>
                                                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                                                    <ul class="divide-y divide-gray-100">
                                                        @foreach($req->submitted_data as $key => $value)
                                                            <li class="p-4 flex flex-col sm:flex-row sm:items-center hover:bg-gray-50/50 transition-colors gap-1 sm:gap-4">
                                                                <span class="text-sm font-semibold text-gray-500 capitalize w-full sm:w-1/3 shrink-0">{{ str_replace('_', ' ', $key) }}</span>
                                                                <span class="text-sm font-bold text-gray-900 flex-1">{{ $value }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Modal Footer -->
                                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 shrink-0 flex justify-end">
                                        <button type="button" @click="showModal = false" class="inline-flex items-center gap-2 justify-center rounded-xl border border-transparent px-6 py-2.5 bg-gray-900 text-sm font-bold text-white shadow-lg hover:bg-gray-800 hover:-translate-y-0.5 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 w-full sm:w-auto">
                                            Tutup Detail
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </template>
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

@push('scripts')
@if($isProfileIncomplete)
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            title: 'Perhatian!',
            text: 'Halo! Profil biodata Anda belum lengkap. Silakan lengkapi profil Anda terlebih dahulu agar dapat mulai mengajukan surat.',
            icon: 'warning',
            confirmButtonText: 'Lengkapi Profil Sekarang',
            confirmButtonColor: '#3b82f6',
            allowOutsideClick: false,
            allowEscapeKey: false,
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('citizen.profile') }}";
            }
        });
    });
</script>
@endif
@endpush
@endsection
