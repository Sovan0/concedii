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

    function login() {
        if (Auth()->check()) {
            return redirect(route('home'));
        }

        return view('login');
    }

    function registrationPost(Request $request) {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required',
        ]);

//        $data['name'] = $request->name;
//        $data['email'] = $request->email;
//        $data['password'] = Hash::make($request->password);
//        $data['role'] = $request->role;

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

    function logout() {
        auth()->logout();

        return redirect(route('login'));
    }
}
