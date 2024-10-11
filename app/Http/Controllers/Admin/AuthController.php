<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('admin.pages.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            "email" => ["required", "email", "string"],
            "password" => ["required", "string"]
        ]);

        if(!Auth::guard('admin')->attempt($credentials)) {
            return redirect(route('admin.login'))
                ->withErrors(['email' => 'Пользователь не найден, либо данные введены неверно.'])
                ->withInput(['email' => $request->email ?? '']);
        }

        return redirect()->intended(route('admin.home'));
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect(route('admin.login'));
    }
}
