<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Services\FonnteService;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nik' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('citizen.dashboard');
        }

        return back()->withErrors([
            'nik' => 'NIK atau password salah.',
        ])->onlyInput('nik');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nik' => ['required', 'numeric', 'digits:16', 'unique:users,nik'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'numeric'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        // Generate 6 digit OTP
        $otp = (string) rand(100000, 999999);

        // Store registration data and OTP in session
        $request->session()->put('register_data', $validated);
        $request->session()->put('otp_code', $otp);

        // Send WhatsApp OTP via Fonnte
        $message = "Halo {$validated['name']},\n\nKode OTP pendaftaran Portal Kelurahan Anda adalah: *{$otp}*\n\nJANGAN BERIKAN KODE INI KEPADA SIAPAPUN.";
        FonnteService::sendMessage($validated['phone'], $message);

        return redirect()->route('otp.show');
    }

    public function showOtp(Request $request)
    {
        if (!$request->session()->has('register_data')) {
            return redirect()->route('register');
        }

        return view('auth.otp', [
            'phone' => $request->session()->get('register_data')['phone']
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        if (!$request->session()->has('register_data') || !$request->session()->has('otp_code')) {
            return redirect()->route('register')->withErrors(['nik' => 'Sesi pendaftaran habis, silakan daftar ulang.']);
        }

        if ($request->otp !== $request->session()->get('otp_code')) {
            return back()->withErrors(['otp' => 'Kode OTP salah. Silakan coba lagi.']);
        }

        $validated = $request->session()->get('register_data');

        $user = User::create([
            'nik' => $validated['nik'],
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'role' => 'warga',
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        // Clear session data
        $request->session()->forget(['register_data', 'otp_code']);

        return redirect()->route('citizen.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
