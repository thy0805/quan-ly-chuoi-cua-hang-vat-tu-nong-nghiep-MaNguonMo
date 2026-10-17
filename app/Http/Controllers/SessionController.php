<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create()
    {
        return Auth::check() ? redirect()->route('products.index') : view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $key = 'mnm-login:'.hash('sha256', mb_strtolower($request->string('username')->toString()).'|'.$request->ip());
        if (RateLimiter::tooManyAttempts($key, 5)) {
            abort(429, 'Đăng nhập quá nhiều lần. Vui lòng thử lại sau.');
        }
        if (! Auth::attempt([...$request->safe()->only(['username', 'password']), 'is_active' => true])) {
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages(['username' => 'Thông tin đăng nhập không hợp lệ hoặc tài khoản đã ngừng hoạt động.']);
        }
        RateLimiter::clear($key);
        $request->session()->regenerate(true);

        return $request->expectsJson() ? response()->json(['user' => $request->user()]) : redirect()->intended(route('products.index'));
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $request->expectsJson() ? response()->noContent() : redirect()->route('login');
    }
}
