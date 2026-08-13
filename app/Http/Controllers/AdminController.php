<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LetterRequest;
use App\Models\LetterType;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_warga' => User::where('role', 'warga')->count(),
            'total_surat_menunggu' => LetterRequest::where('status', 'menunggu')->count(),
            'total_surat_selesai' => LetterRequest::where('status', 'selesai')->count(),
            'total_jenis_surat' => LetterType::count(),
        ];

        $latestRequests = LetterRequest::with(['user', 'letterType'])
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        return view('dashboard.admin.index', compact('stats', 'latestRequests'));
    }
}
