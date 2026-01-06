<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * 1. Redirect User ke Halaman Login Google
     * Endpoint: /api/auth/google
     */
    public function redirectToGoogle()
    {
        // Menggunakan stateless() karena arsitektur API (tanpa session)
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * 2. Handle Callback (Saat user balik dari Google)
     * Endpoint: /api/auth/google/callback
     */
    public function handleGoogleCallback()
    {
        // URL Frontend (Next.js) diambil dari .env, default ke localhost:3000
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');

        try {
            // Ambil data user dari Google
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Cek apakah user dengan email ini sudah ada di database?
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // --- KASUS: USER BARU (Auto Register) ---
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(), // Ambil foto dari Google
                    'role' => 'student', // Default role
                    'password' => null, // Tidak butuh password
                    'email_verified_at' => now(), // Anggap email valid karena dari Google
                ]);
            } else {
                // --- KASUS: USER LAMA (Account Merging) ---
                // Jika user daftar manual lalu login Google, kita gabungkan akunnya.
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(), // Update foto biar fresh
                ]);
            }

            // BUAT TOKEN SANCTUM (Auto Login)
            $token = $user->createToken('google-auth-token')->plainTextToken;

            // --- REDIRECT KE FRONTEND ---
            // Kita lempar user kembali ke Next.js sambil membawa Token & Data User di URL
            // Contoh: http://localhost:3000/auth/callback?token=abcd...&role=student

            $redirectQuery = http_build_query([
                'token' => $token,
                'user_name' => $user->name,
                'role' => $user->role,
                'avatar' => $user->avatar,
                'status' => 'success'
            ]);

            return redirect($frontendUrl . '/auth/callback?' . $redirectQuery);
        } catch (\Exception $e) {
            // Jika Error (misal user membatalkan login atau koneksi putus)
            Log::error('Google Login Error: ' . $e->getMessage());

            // --- UBAH BAGIAN INI ---
            // Jangan redirect dulu, kita mau lihat errornya apa.
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(), // Tampilkan pesan error asli
                'trace' => $e->getTraceAsString()
            ], 500);

            // KODE LAMA (Komentari dulu):
            // return redirect($frontendUrl . '/auth/login?error=google_login_failed');
        }
    }
}
