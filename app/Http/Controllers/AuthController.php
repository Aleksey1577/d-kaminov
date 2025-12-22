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

    public function showLoginForm(Request $request)
    {
        $categories = $this->getCategories();
        $captchaQuestion = $this->makeCaptcha($request);
        return view('auth.login', compact('categories', 'captchaQuestion'))
            ->with('breadcrumbs', [
                ['name' => 'Главная', 'url' => route('home')],
                ['name' => 'Вход', 'url' => null],
            ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
            'captcha'  => ['required', 'numeric'],
        ]);

        if (!$this->checkCaptcha($request)) {
            return back()->withErrors(['captcha' => 'Неверно решён пример.'])->withInput();
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            $request->session()->forget('captcha_answer');

            if (Auth::user()->is_admin) {
                return redirect()->intended('/admin');
            }

            return redirect()->intended(route('profile'));
        }

        return back()->withErrors([
            'email' => 'Неверные данные.',
        ])->onlyInput('email');
    }

    public function showRegisterForm(Request $request)
    {
        $categories = $this->getCategories();
        $captchaQuestion = $this->makeCaptcha($request);
        return view('auth.register', compact('categories', 'captchaQuestion'))
            ->with('breadcrumbs', [
                ['name' => 'Главная', 'url' => route('home')],
                ['name' => 'Регистрация', 'url' => null],
            ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'captcha'               => ['required', 'numeric'],
        ]);

        if (!$this->checkCaptcha($request)) {
            return back()->withErrors(['captcha' => 'Неверно решён пример.'])->withInput();
        }

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => false,
        ]);

        Auth::login($user);
        $request->session()->forget('captcha_answer');

        return redirect()->route('profile');
    }

    public function profile()
    {

        $user = Auth::user();

        $categories      = $this->getCategories();
        $favorites       = session('favorites', []);
        $compare         = session('compare', []);
        $favoritesCount  = is_array($favorites) ? count($favorites) : 0;
        $compareCount    = is_array($compare) ? count($compare) : 0;

        $orders = method_exists($user, 'orders') ? $user->orders()->latest()->get() : collect();

        return view('profile', compact('user', 'categories', 'favoritesCount', 'compareCount', 'orders'))
            ->with('breadcrumbs', [
                ['name' => 'Главная', 'url' => route('home')],
                ['name' => 'Личный кабинет', 'url' => null],
            ]);
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

        $user = Auth::user();
        $user->fill($validated)->save();

        return back()->with('success', 'Профиль обновлён');
    }

    protected function makeCaptcha(Request $request): string
    {
        $a = random_int(2, 9);
        $b = random_int(1, 9);
        $request->session()->put('captcha_answer', $a + $b);
        return "{$a} + {$b}";
    }

    protected function checkCaptcha(Request $request): bool
    {
        $expected = $request->session()->pull('captcha_answer');
        return $expected !== null && (int)$request->input('captcha') === (int)$expected;
    }
}
