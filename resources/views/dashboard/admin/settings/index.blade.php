@extends('layouts.app')

@section('header_title', 'Pengaturan & Arsip')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="{ tab: 'ttd' }">

    @if(session('success'))
        <div class="bg-green-50 border border-green-100 text-green-700 p-4 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-100 text-red-700 p-4 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            <span class="font-bold">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Tabs Header -->
    <div class="bg-white rounded-2xl p-2 border border-gray-100 flex gap-2 w-max">
        <button @click="tab = 'ttd'" :class="tab === 'ttd' ? 'bg-primary-50 text-primary-600 font-bold' : 'text-gray-500 font-medium hover:bg-gray-50'" class="px-6 py-2 rounded-xl transition-all">
            Pejabat Penandatangan
        </button>
        <button @click="tab = 'arsip'" :class="tab === 'arsip' ? 'bg-primary-50 text-primary-600 font-bold' : 'text-gray-500 font-medium hover:bg-gray-50'" class="px-6 py-2 rounded-xl transition-all">
            Backup & Manajemen Arsip
        </button>
    </div>

    <!-- Tab Content: Penandatangan -->
    <div x-show="tab === 'ttd'" class="space-y-6" x-transition.opacity>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Form Add -->
            <div class="col-span-1 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-4">Tambah Pejabat Baru</h3>
                <form action="{{ route('admin.settings.signatories.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Pejabat</label>
                        <input type="text" name="name" required placeholder="Contoh: Dini Handiani Fazhar, S.IP" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jabatan</label>
                        <input type="text" name="position" required placeholder="Contoh: a.n Lurah Sukapada KASI PEMERINTAHAN" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">NIP</label>
                        <input type="text" name="nip" placeholder="Contoh: NIP. 19900810 201903 2 006" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
                    </div>
                    <button type="submit" class="w-full px-4 py-3 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-500/30">
                        Simpan Data
                    </button>
                </form>
            </div>

            <!-- List Signatories -->
            <div class="col-span-1 md:col-span-2 bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-900">Daftar Penandatangan Aktif</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($signatories as $sig)
                        <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 hover:bg-gray-50 transition-colors">
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">{{ $sig->name }}</h4>
                                <p class="text-sm text-gray-500 font-medium">{{ $sig->position }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $sig->nip ?? 'Tidak ada NIP' }}</p>
                            </div>
                            <form action="{{ route('admin.settings.signatories.destroy', $sig->id) }}" method="POST" onsubmit="return confirm('Hapus penandatangan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 font-bold rounded-lg hover:bg-red-100 transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">Belum ada data penandatangan.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Content: Arsip -->
    <div x-show="tab === 'arsip'" style="display: none;" class="space-y-6" x-transition.opacity>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Backup ZIP -->
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-6 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </div>
                    <h3 class="font-bold text-xl text-gray-900 mb-2">Backup Arsip Berkas (.zip)</h3>
                    <p class="text-gray-500 text-sm mb-6 leading-relaxed">Unduh seluruh berkas PDF dan foto (KTP, KK, dll) yang pernah diunggah warga. Tersusun rapi dalam folder berdasarkan <b>Nama Warga</b> dan <b>Jenis Surat</b>.</p>
                    <a href="{{ route('admin.settings.backup') }}" class="inline-block w-full text-center px-4 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30">
                        Mulai Unduh ZIP Backup
                    </a>
                </div>
            </div>

            <!-- Clean Arsip -->
            <div class="bg-white p-8 rounded-3xl border border-red-100 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-red-50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-14 h-14 bg-red-100 text-red-600 rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </div>
                        <div class="bg-red-50 text-red-600 text-xs font-bold px-3 py-1 rounded-full border border-red-100">
                            {{ $archiveSize }} MB Digunakan
                        </div>
                    </div>
                    
                    <h3 class="font-bold text-xl text-gray-900 mb-2">Format / Kosongkan Ruang</h3>
                    <p class="text-gray-500 text-sm mb-6 leading-relaxed">Menghapus seluruh file lampiran fisik (KTP, KK, dll) dari server untuk menghemat ruang *hosting*. Riwayat pengajuan dan teks akan tetap ada.</p>
                    <form action="{{ route('admin.settings.clean') }}" method="POST" onsubmit="return confirm('PERINGATAN! File fisik akan dihapus dari server secara permanen. Pastikan Anda sudah mem-backup data arsip. Lanjutkan?');">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 bg-white border-2 border-red-200 text-red-600 font-bold rounded-xl hover:bg-red-50 transition-all">
                            Format Ruang Penyimpanan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
    </div>

</div>
@endsection
