@extends('layouts.app')

@section('header_title', 'Data Pengajuan Surat')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Data Pengajuan</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola permohonan surat masuk dari seluruh warga.</p>
        </div>
        <div class="flex gap-2">
            <form action="{{ route('admin.letter-requests.index') }}" method="GET" class="flex gap-2">
                <select name="status" onchange="this.form.submit()" class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none text-sm font-semibold text-gray-700">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="siap_diambil" {{ request('status') === 'siap_diambil' ? 'selected' : '' }}>Siap Diambil</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-100 text-green-700 p-4 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Requests Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50/50 text-gray-500 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Tgl Pengajuan</th>
                        <th class="px-6 py-4 font-semibold">Pemohon</th>
                        <th class="px-6 py-4 font-semibold">Jenis Surat</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($requests as $req)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $req->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $req->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-primary-600 to-primary-400 flex items-center justify-center text-white font-bold shrink-0 text-xs">
                                        {{ substr($req->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $req->user->name }}</div>
                                        <div class="text-xs text-gray-500 font-mono">{{ $req->user->nik }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-700">{{ $req->letterType->code }}</div>
                                <div class="text-xs text-gray-500">{{ Str::limit($req->letterType->name, 35) }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
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
                                <a href="{{ route('admin.letter-requests.show', $req->id) }}" class="inline-flex items-center justify-center py-2 px-4 rounded-lg bg-primary-50 text-primary-700 hover:bg-primary-100 font-bold text-xs transition-colors">
                                    Proses & Tinjau
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">Belum ada pengajuan surat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection
