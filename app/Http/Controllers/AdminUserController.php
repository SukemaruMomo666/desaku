<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'warga')
            ->withCount('letterRequests')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('dashboard.admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with(['letterRequests.letterType'])->findOrFail($id);
        return view('dashboard.admin.users.show', compact('user'));
    }
}
