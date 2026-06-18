<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials, $request->boolean('remember'))){
            $request->session()->regenerate();
            return redirect()->intended(route('workspace.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The email or password is incorrect.'
        ])->onlyInput('email');
    }

    public function signup(Request $request){
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|max:255|unique:users,email',
            'username' => 'required|string',
            'password' => 'required|string|min:4|confirmed|max:255'
        ]);

        try {
            $user = User::create([
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
            Auth::login($user);
            $request->session()->regenerate();
        } catch (QueryException $e) {
            return back()->withErrors([
                'email' => "Registration can't be proccess"
            ]);
        }

        return redirect()->route('workspace.dashboard');
        

        
    }


    public function google(){
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback(){
        
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->email)->first();
            if(!$user){
                $user = User::create([
                    'name' => $googleUser->name,
                    'username'=> $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => bcrypt(Str::random(16))
                ]);
            }

            
            Auth::login($user);

            return redirect()->intendend(route('workspace.dashboard'));
        } catch (Exception $e) {
            return redirect()->route('signin')->with('error', 'cant login with google, please try again' . $e);
        }
    }

    public function facebook(){
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookCallback(){
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            $user = User::where('email', $facebookUser->email)->first();
            if(!$user){
                $user = User::create([
                    'name' => $facebookUser->name,
                    'username'=> $facebookUser->name,
                    'email' => $facebookUser->email,
                    'password' => bcrypt(Str::random(16))
                ]);
            }

            
            Auth::login($user);

            return redirect()->intendend(route('workspace.dashboard'));
        } catch (Exception $e) {
            return redirect()->route('signin')->with('error', 'cant login with facebook, please try again' . $e);
        }
    }

    public function logout(Request $request){
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('signin');
    }
}
