<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\SocialAccount;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Socialite;

class AuthController extends Controller
{
    public function signinView(){
        return view('pages.auth.signin');
    }

    public function signupView(){
        return view('pages.auth.signup');
    }

    public function signin(Request $request){

        $credentials =  $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        if(Auth::attempt($credentials, $request->boolean('remember'))){
            $request->session()->regenerate();
            return redirect()->intended(route('workspace.dashboard'));
        }
        return back()->withErrors([
            'error' => 'The email or password is incorrect.'
        ])->onlyInput('email');
    }

    public function forgotPasswordView(){
        return view('pages.auth.forget-password');
    }


    public function google(){
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback(){
        
        try {
            $googleUser = Socialite::driver('google')->user();
            

            $user = User::query()->where('email', $googleUser->email)->first();
            
            if(!$user){
                $user = User::create([
                    'name' => $googleUser->name,
                    'username'=> $googleUser->name,
                    'email' => $googleUser->email,
                    'profile_photo_path' => $googleUser->avatar,
                    'password' => bcrypt(Str::random(16))
                ]);
            }
            Auth::login($user);

            return redirect()->intended(route('workspace.dashboard'));
        } catch (Exception $e) {
            return redirect()->route('signin')->with('error', 'cant login with google, please try again');
        }
    }


    public function logout(Request $request){
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('signin');
    }
}
