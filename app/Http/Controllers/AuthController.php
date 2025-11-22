<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\CommonDataTrait;

class AuthController extends Controller
{
    use CommonDataTrait;

    public function showLoginForm()
    {
        $categories = $this->getCategories();
        return view('auth.login', compact('categories'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Проверка на админа по is_admin
            if (Auth::user()->is_admin) {
                return redirect()->intended('/admin');
            }

            // Обычный пользователь — в личный кабинет
            return redirect()->intended('/profile');
        }

        return back()->withErrors([
            'email' => 'Неверные данные.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        $categories = $this->getCategories();
        return view('auth.register', compact('categories'));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => false,  // По умолчанию не админ
        ]);

        Auth::login($user);

        // После регистрации — в личный кабинет
        return redirect('/profile');
    }

    public function profile()
    {
        $user = Auth::user();
        $categories = $this->getCategories();
        return view('profile', compact('user', 'categories'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
        ]);

        Auth::user()->update($validated);

        return back()->with('success', 'Профиль обновлён');
    }
}
