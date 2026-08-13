@extends('layouts.app')

@section('header_title', 'Proses Pengajuan Surat')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <a href="{{ route('admin.letter-requests.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
        <a href="{{ route('admin.letter-requests.print', $letterRequest->id) }}" target="_blank" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 hover:-translate-y-0.5 hover:bg-blue-700 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Unduh Surat (Word)
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-100 text-green-700 p-4 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Kolom Kiri: Info Warga & Form Isian -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Profil Singkat Warga -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4 w-full">
                    <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center text-2xl font-bold text-gray-400 shrink-0">
                        {{ substr($letterRequest->user->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">{{ $letterRequest->user->name }}</h3>
                        <p class="text-sm font-mono text-gray-500">NIK: {{ $letterRequest->user->nik }}</p>
                    </div>
                </div>
                <div class="text-right sm:block w-full sm:w-auto">
                    <a href="{{ route('admin.users.show', $letterRequest->user->id) }}" class="text-sm font-bold text-primary-600 hover:text-primary-700 transition-colors inline-block bg-primary-50 px-4 py-2 rounded-lg sm:bg-transparent sm:px-0 sm:py-0 w-full text-center sm:text-right">Lihat Detail Warga &rarr;</a>
                </div>
            </div>

            <!-- Detail Formulir Pengajuan -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-lg text-gray-900">Formulir Isian Warga</h3>
                    <p class="text-sm text-gray-500 mt-1">Data yang diisi oleh warga saat mengajukan permohonan.</p>
                </div>
                
                <div class="p-6">
                    @if($letterRequest->submitted_data && count($letterRequest->submitted_data) > 0)
                        <dl class="space-y-4">
                            @foreach($letterRequest->submitted_data as $key => $value)
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ str_replace('_', ' ', $key) }}</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 text-sm">Tidak ada data isian formulir (hanya pengajuan default).</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Lampiran Dokumen -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-lg text-gray-900">Lampiran Dokumen</h3>
                    <p class="text-sm text-gray-500 mt-1">Syarat fisik/foto yang diunggah warga.</p>
                </div>
                
                <div class="p-6">
                    @if($letterRequest->uploaded_files && count($letterRequest->uploaded_files) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($letterRequest->uploaded_files as $key => $path)
                                <a href="{{ asset('storage/' . $path) }}" target="_blank" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 hover:border-primary-300 hover:shadow-sm transition-all group">
                                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:bg-primary-600 group-hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    </div>
                                    <div class="overflow-hidden">
                                        <div class="text-xs font-semibold text-gray-500 uppercase truncate">{{ str_replace('_', ' ', $key) }}</div>
                                        <div class="text-sm font-bold text-primary-600 truncate">Lihat Dokumen</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 text-sm">Warga tidak mengunggah lampiran dokumen.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Kolom Kanan: Status & Tindakan -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden sticky top-24">
                
                <div class="px-6 py-6 bg-gradient-to-br from-primary-600 to-primary-800 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="text-primary-200 text-xs font-bold tracking-widest uppercase mb-1">{{ $letterRequest->letterType->code }}</div>
                        <h2 class="text-xl font-bold leading-tight mb-4">{{ $letterRequest->letterType->name }}</h2>
                        
                        <div class="flex items-center justify-between text-xs font-medium text-primary-100 bg-black/10 p-3 rounded-xl border border-white/10">
                            <div>Diajukan pada:</div>
                            <div class="font-bold text-white">{{ $letterRequest->created_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.letter-requests.update-status', $letterRequest->id) }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Update Status Pengajuan</label>
                        <select name="status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none font-semibold text-gray-700 transition-all">
                            <option value="menunggu" {{ $letterRequest->status === 'menunggu' ? 'selected' : '' }}>🕒 Menunggu</option>
                            <option value="diproses" {{ $letterRequest->status === 'diproses' ? 'selected' : '' }}>⚙️ Sedang Diproses</option>
                            <option value="siap_diambil" {{ $letterRequest->status === 'siap_diambil' ? 'selected' : '' }}>✅ Siap Diambil</option>
                            <option value="selesai" {{ $letterRequest->status === 'selesai' ? 'selected' : '' }}>🏁 Selesai</option>
                            <option value="ditolak" {{ $letterRequest->status === 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Catatan Admin (Opsional)</label>
                        <textarea name="admin_notes" rows="4" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none text-sm transition-all resize-none" placeholder="Misal: 'Silakan ambil di Balai Desa dengan membawa KTP Asli' atau 'Ditolak karena foto KTP buram'">{{ $letterRequest->admin_notes }}</textarea>
                        <p class="text-xs text-gray-500 mt-2">Catatan ini dapat dibaca oleh warga di dashboard mereka.</p>
                    </div>

                    <button type="submit" class="w-full px-4 py-3 bg-primary-600 text-white font-bold rounded-xl shadow-lg shadow-primary-500/30 hover:-translate-y-0.5 hover:bg-primary-700 transition-all mb-4">
                        Simpan Perubahan
                    </button>

                </form>

            </div>
        </div>

    </div>

</div>
@endsection
