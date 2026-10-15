<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Socialite;

class AuthController extends Controller
{
    public function signinView()
    {
        return view('pages.auth.signin');
    }

    public function signupView()
    {
        return view('pages.auth.signup');
    }

    public function signin(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('workspace.dashboard'));
        }

        return back()->withErrors([
            'error' => 'The email or password is incorrect.',
        ])->onlyInput('email');
    }

    public function forgotPasswordView()
    {
        return view('pages.auth.forget-password');
    }

    public function google()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {

        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::query()->where('email', $googleUser->email)->first();

            if (! $user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'username' => $this->uniqueUsername($googleUser->name, $googleUser->email),
                    'email' => $googleUser->email,
                    'profile_photo_path' => $googleUser->avatar,
                    'password' => bcrypt(Str::random(16)),
                ]);
            }
            Auth::login($user);

            return redirect()->intended(route('workspace.dashboard'));
        } catch (Exception $e) {
            return redirect()->route('signin')->with('error', 'cant login with google, please try again');
        }
    }

    private function uniqueUsername(string $name, string $email): string
    {
        $base = Str::of(Str::before($email, '@'))->lower()->replaceMatches('/[^a-z0-9_.]/', '')->toString();
        $base = Str::limit($base !== '' ? $base : Str::slug($name, '_'), 24, '');
        $username = $base;

        while (User::query()->whereIn('username', [$username, '@'.$username])->exists()) {
            $username = $base.random_int(100, 9999);
        }

        return $username;
    }

    public function logout(Request $request)
    {

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('signin');
    }
}
