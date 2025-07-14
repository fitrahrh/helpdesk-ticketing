<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Override method ini untuk menambahkan session flash setelah login
     */
    protected function authenticated(Request $request, $user)
    {
        // Set session flash untuk toastr
        session()->flash('status', 'Login berhasil!');
    }

    /**
     * Override method logout untuk menambahkan session flash setelah logout
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Set session flash untuk toastr
        $request->session()->flash('status', 'Logout berhasil!');

        return redirect()->route('login');
    }
}
