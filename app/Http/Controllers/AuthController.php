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
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->is_admin) {
                return redirect()->intended('/admin');
            }

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
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => false,
        ]);

        Auth::login($user);

        return redirect('/profile');
    }

    public function profile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $categories      = $this->getCategories();
        $favorites       = session('favorites', []);
        $compare         = session('compare', []);
        $favoritesCount  = is_array($favorites) ? count($favorites) : 0;
        $compareCount    = is_array($compare) ? count($compare) : 0;

        $orders = method_exists($user, 'orders') ? $user->orders()->latest()->get() : collect();

        return view('profile', compact('user', 'categories', 'favoritesCount', 'compareCount', 'orders'));
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
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->fill($validated)->save();

        return back()->with('success', 'Профиль обновлён');
    }
}
