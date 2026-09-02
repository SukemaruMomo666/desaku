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
            <button class="text-sm font-semibold text-primary-600 hover:text-primary-700">Lihat Semua</button>
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
                        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" @click="showModal = false" aria-hidden="true"></div>
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                <div x-show="showModal" x-transition class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full">
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="sm:flex sm:items-start">
                                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                                <h3 class="text-xl leading-6 font-bold text-gray-900 border-b pb-3" id="modal-title">
                                                    Detail Pengajuan
                                                </h3>
                                                <div class="mt-4 space-y-4 text-sm text-gray-700 text-left">
                                                    <div class="grid grid-cols-3 gap-2">
                                                        <span class="font-semibold text-gray-500">Jenis Surat</span>
                                                        <span class="col-span-2 font-medium">{{ $req->letterType->name }}</span>
                                                    </div>
                                                    <div class="grid grid-cols-3 gap-2">
                                                        <span class="font-semibold text-gray-500">Tanggal</span>
                                                        <span class="col-span-2 font-medium">{{ $req->created_at->format('d M Y H:i') }}</span>
                                                    </div>
                                                    <div class="grid grid-cols-3 gap-2">
                                                        <span class="font-semibold text-gray-500">Status</span>
                                                        <span class="col-span-2">
                                                            <span class="px-2 py-0.5 rounded-full text-xs font-bold uppercase {{ $color }}">{{ $req->status }}</span>
                                                        </span>
                                                    </div>
                                                    
                                                    @if($req->admin_notes)
                                                        <div class="p-3 bg-red-50 text-red-700 rounded-lg border border-red-100 mt-2">
                                                            <strong>Catatan Penolakan:</strong> {{ $req->admin_notes }}
                                                        </div>
                                                    @endif

                                                    @if($req->submitted_data && count($req->submitted_data) > 0)
                                                    <div class="mt-4">
                                                        <strong class="block mb-2 text-gray-900">Data Isian Form:</strong>
                                                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 space-y-2">
                                                            @foreach($req->submitted_data as $key => $value)
                                                                <div class="grid grid-cols-3 gap-2">
                                                                    <span class="font-semibold text-gray-500 capitalize">{{ str_replace('_', ' ', $key) }}</span>
                                                                    <span class="col-span-2 text-gray-900">{{ $value }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                                        <button type="button" @click="showModal = false" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-gray-900 text-base font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                            Tutup
                                        </button>
                                    </div>
                                </div>
                            </div>
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
