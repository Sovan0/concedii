<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Auth extends Controller
{
    function registration() {
        if (Auth()->check()) {
            return redirect(route('home'));
        }

        return view('registration');
    }

    function login(Request $request) {
        $request->session()->forget(['start_date', 'end_date']);
        if (Auth()->check()) {
            return redirect(route('home'));
        }

        return view('login');
    }

    function registrationPost(Request $request) {
        $data = $request->validate([
            'name' => 'required|regex:/^[a-zA-Z]+$/u',
            'email' => ['required', 'email', 'unique:users', 'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/'],
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6'
        ]);

        $user = User::create($data);

        if(!$user) {
            return redirect(route('registration'))->with("error", "Registration failed, try again.");
        }

        return redirect(route('login'))->with("success", "Registration success, Login to access the app.");
    }

    function loginPost(Request $request) {
        $attributes = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

//        $credentials = $request->only('email', 'password');

        if(Auth()->attempt($attributes)) {
            return redirect()->intended(route('home'));
        }

        return redirect(route('login'))->with("error", "Login Details are not valid.");
    }

    function logout(Request $request) {
        $request->session()->forget(['start_date', 'end_date']);
        auth()->logout();

        return redirect(route('login'));
    }
}
