<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            return redirect()->route('workspace.dashboard');
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

    public function logout(Request $request){
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('signin');
    }
}
