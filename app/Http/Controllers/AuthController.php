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
            
            if (in_array(Auth::user()->role, ['master_admin', 'super_admin', 'admin'])) {
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

    // ==========================================
    // FORGOT PASSWORD FLOW
    // ==========================================

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'nik' => ['required', 'string', 'digits:16'],
        ]);

        $user = User::where('nik', $request->nik)->first();

        if (!$user) {
            return back()->withErrors(['nik' => 'NIK tidak ditemukan di sistem.'])->withInput();
        }

        if (!$user->phone) {
            return back()->withErrors(['nik' => 'Akun Anda tidak memiliki nomor WhatsApp yang terdaftar. Hubungi admin.'])->withInput();
        }

        // Generate 6 digit OTP
        $otp = (string) rand(100000, 999999);

        // Store reset data in session
        $request->session()->put('reset_nik', $user->nik);
        $request->session()->put('reset_otp', $otp);

        // Send WhatsApp OTP via Fonnte
        $message = "Halo {$user->name},\n\nKode OTP untuk mengatur ulang kata sandi Anda adalah: *{$otp}*\n\nJANGAN BERIKAN KODE INI KEPADA SIAPAPUN. Jika Anda tidak meminta ini, abaikan pesan ini.";
        FonnteService::sendMessage($user->phone, $message);

        return redirect()->route('password.reset.otp.show');
    }

    public function showResetOtp(Request $request)
    {
        if (!$request->session()->has('reset_nik')) {
            return redirect()->route('password.forgot');
        }

        $user = User::where('nik', $request->session()->get('reset_nik'))->first();

        return view('auth.reset-otp', [
            'phone' => $user->phone
        ]);
    }

    public function verifyResetOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        if (!$request->session()->has('reset_nik') || !$request->session()->has('reset_otp')) {
            return redirect()->route('password.forgot')->withErrors(['nik' => 'Sesi habis, silakan mulai ulang proses lupa kata sandi.']);
        }

        if ($request->otp !== $request->session()->get('reset_otp')) {
            return back()->withErrors(['otp' => 'Kode OTP salah. Silakan coba lagi.']);
        }

        // OTP Valid, flag session as verified
        $request->session()->put('reset_otp_verified', true);

        return redirect()->route('password.reset.show');
    }

    public function showResetPassword(Request $request)
    {
        if (!$request->session()->has('reset_nik') || !$request->session()->get('reset_otp_verified')) {
            return redirect()->route('password.forgot');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        if (!$request->session()->has('reset_nik') || !$request->session()->get('reset_otp_verified')) {
            return redirect()->route('password.forgot');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
        ]);

        $user = User::where('nik', $request->session()->get('reset_nik'))->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Clear session data
        $request->session()->forget(['reset_nik', 'reset_otp', 'reset_otp_verified']);

        return redirect()->route('login')->with('success', 'Kata sandi berhasil diatur ulang! Silakan masuk menggunakan kata sandi baru Anda.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
