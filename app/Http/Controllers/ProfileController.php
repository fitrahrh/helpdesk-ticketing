<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nip' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'telegram_id' => 'nullable|string|max:255',
        ]);

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->nip = $request->input('nip');
        $user->no_hp = $request->input('no_hp');
        $user->telegram_id = $request->input('telegram_id');
        $user->save();

        return redirect()->route('profile.edit')->with('status', 'Profil Berhasil Di Update!');
    }

    public function changepassword()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function password(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password Sebelumnya Salah!']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('status', 'Password berhasil Diubah!');
    }
    public function testTelegram(Request $request)
    {
        $telegram_id = $request->input('telegram_id');
        if (!$telegram_id) {
            return response()->json(['message' => 'Telegram ID tidak ditemukan'], 422);
        }

        try {
            // Kirim pesan ke Telegram
            \Telegram::sendMessage([
                'chat_id' => $telegram_id,
                'text' => 'Tes notifikasi berhasil! Akun Anda sudah terhubung dengan sistem Helpdesk.'
            ]);
            return response()->json(['message' => 'Notifikasi berhasil dikirim ke Telegram Anda']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengirim notifikasi: ' . $e->getMessage()], 500);
        }
    }
}