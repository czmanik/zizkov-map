<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function loginView()
    {
        return view('auth.login');
    }

    public function registerView()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Zadané údaje jsou nesprávné.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nickname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'terms' => ['accepted'],
        ]);

        $user = User::create([
            'name' => $request->nickname, // Using nickname as name initially
            'nickname' => $request->nickname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'terms_accepted_at' => now(),
        ]);

        Auth::login($user);

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Přihlášení selhalo.');
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Update existing user but keep their role
            $user->update([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ]);
        } else {
            // Create new visitor
            $user = User::create([
                'name' => $socialUser->getName(),
                'nickname' => $socialUser->getNickname() ?? $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'role' => 'user',
                'terms_accepted_at' => now(), // Assume accepted if they use social login for simplicity or add a step
                'password' => Hash::make(str()->random(24)), // Random password for social users
            ]);
        }

        Auth::login($user);

        return redirect('/');
    }
}
