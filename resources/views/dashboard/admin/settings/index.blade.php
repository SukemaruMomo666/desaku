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
    <div class="bg-white rounded-2xl p-2 border border-gray-100 flex gap-2 w-max flex-wrap">
        <button @click="tab = 'ttd'" :class="tab === 'ttd' ? 'bg-primary-50 text-primary-600 font-bold' : 'text-gray-500 font-medium hover:bg-gray-50'" class="px-6 py-2 rounded-xl transition-all">
            Pejabat Penandatangan
        </button>
        <button @click="tab = 'arsip'" :class="tab === 'arsip' ? 'bg-primary-50 text-primary-600 font-bold' : 'text-gray-500 font-medium hover:bg-gray-50'" class="px-6 py-2 rounded-xl transition-all">
            Backup & Manajemen Arsip
        </button>
        <button @click="tab = 'akun'" :class="tab === 'akun' ? 'bg-primary-50 text-primary-600 font-bold' : 'text-gray-500 font-medium hover:bg-gray-50'" class="px-6 py-2 rounded-xl transition-all">
            Manajemen Akun Admin
        </button>
        <button @click="tab = 'role'" :class="tab === 'role' ? 'bg-primary-50 text-primary-600 font-bold' : 'text-gray-500 font-medium hover:bg-gray-50'" class="px-6 py-2 rounded-xl transition-all">
            Hak Akses Role
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

    <!-- Tab Content: Manajemen Akun Admin -->
    <div x-show="tab === 'akun'" style="display: none;" class="space-y-6" x-transition.opacity>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Form Add -->
            <div class="col-span-1 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm self-start">
                <h3 class="font-bold text-gray-900 mb-4">Tambah Akun Admin</h3>
                <form action="{{ route('admin.settings.accounts.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Pengguna</label>
                        <input type="text" name="name" required placeholder="Contoh: Admin Pelayanan" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">NIK / Username</label>
                        <input type="text" name="nik" required placeholder="Gunakan NIK atau kata unik" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password</label>
                        <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Hak Akses (Role)</label>
                        <select name="role" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
                            <option value="master_admin">Master Admin (Semua Akses)</option>
                            <option value="super_admin">Super Admin (Kecuali Pengaturan)</option>
                            <option value="admin">Admin Biasa (Hanya Data Pengajuan)</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full px-4 py-3 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-500/30">
                        Buat Akun Admin
                    </button>
                </form>
            </div>

            <!-- List Admins -->
            <div class="col-span-1 md:col-span-2 bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden self-start">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-900">Daftar Akun Admin Terdaftar</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($admins as $adminAcc)
                        <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 hover:bg-gray-50 transition-colors">
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                                    {{ $adminAcc->name }}
                                    @if(auth()->id() === $adminAcc->id)
                                        <span class="bg-primary-100 text-primary-700 text-xs px-2 py-0.5 rounded-full">Anda</span>
                                    @endif
                                </h4>
                                <p class="text-sm text-gray-500 font-medium">Username: {{ $adminAcc->nik }}</p>
                                <div class="mt-2">
                                    @if($adminAcc->role === 'master_admin')
                                        <span class="bg-purple-100 text-purple-700 text-xs px-3 py-1 rounded-full font-bold">Master Admin</span>
                                    @elseif($adminAcc->role === 'super_admin')
                                        <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-bold">Super Admin</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full font-bold">Admin Biasa</span>
                                    @endif
                                </div>
                            </div>
                            @if(auth()->id() !== $adminAcc->id)
                            <form action="{{ route('admin.settings.accounts.destroy', $adminAcc->id) }}" method="POST" onsubmit="return confirm('Hapus akun admin ini? Mereka tidak akan bisa login lagi.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 font-bold rounded-lg hover:bg-red-100 transition-colors">
                                    Cabut Akses
                                </button>
                            </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Content: Hak Akses Role -->
    <div x-show="tab === 'role'" style="display: none;" class="space-y-6" x-transition.opacity>
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm max-w-4xl">
            <div class="mb-6">
                <h3 class="font-bold text-gray-900 text-xl">Pengaturan Hak Akses Role Dinamis</h3>
                <p class="text-gray-500 text-sm mt-1">Centang menu apa saja yang boleh diakses oleh masing-masing tingkat admin. Master Admin memiliki akses penuh mutlak yang tidak bisa dibatasi.</p>
            </div>

            <form action="{{ route('admin.settings.role-permissions.update') }}" method="POST" class="space-y-8">
                @csrf
                
                <!-- Super Admin Permissions -->
                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                    <h4 class="font-bold text-blue-700 text-lg mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Hak Akses: Super Admin
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-blue-500 transition-colors">
                            <input type="checkbox" name="role_super_admin_permissions[]" value="manage_requests" class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500" {{ in_array('manage_requests', $role_super_admin_permissions) ? 'checked' : '' }}>
                            <span class="font-medium text-gray-700">Data Pengajuan Warga</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-blue-500 transition-colors">
                            <input type="checkbox" name="role_super_admin_permissions[]" value="manage_letter_types" class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500" {{ in_array('manage_letter_types', $role_super_admin_permissions) ? 'checked' : '' }}>
                            <span class="font-medium text-gray-700">Master Jenis Surat</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-blue-500 transition-colors">
                            <input type="checkbox" name="role_super_admin_permissions[]" value="manage_users" class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500" {{ in_array('manage_users', $role_super_admin_permissions) ? 'checked' : '' }}>
                            <span class="font-medium text-gray-700">Data Warga</span>
                        </label>
                    </div>
                </div>

                <!-- Admin Biasa Permissions -->
                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                    <h4 class="font-bold text-gray-700 text-lg mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Hak Akses: Admin Biasa
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-gray-500 transition-colors">
                            <input type="checkbox" name="role_admin_permissions[]" value="manage_requests" class="w-5 h-5 text-gray-600 rounded border-gray-300 focus:ring-gray-500" {{ in_array('manage_requests', $role_admin_permissions) ? 'checked' : '' }}>
                            <span class="font-medium text-gray-700">Data Pengajuan Warga</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-gray-500 transition-colors">
                            <input type="checkbox" name="role_admin_permissions[]" value="manage_letter_types" class="w-5 h-5 text-gray-600 rounded border-gray-300 focus:ring-gray-500" {{ in_array('manage_letter_types', $role_admin_permissions) ? 'checked' : '' }}>
                            <span class="font-medium text-gray-700">Master Jenis Surat</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-gray-500 transition-colors">
                            <input type="checkbox" name="role_admin_permissions[]" value="manage_users" class="w-5 h-5 text-gray-600 rounded border-gray-300 focus:ring-gray-500" {{ in_array('manage_users', $role_admin_permissions) ? 'checked' : '' }}>
                            <span class="font-medium text-gray-700">Data Warga</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-500/30">
                        Simpan Konfigurasi Hak Akses
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
