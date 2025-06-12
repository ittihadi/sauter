<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    * Handle authentication attempt
    */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->remember;

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerateToken();

            return redirect()->intended();
        }

        return back()->withErrors([
            'email' => 'Incorrect email or password'
        ])->onlyInput();
    }

    /*
    * Log user out of application
    */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back();
    }

    /*
    * Register a new user
    */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => [
                'required',
                'string',
                'unique:App\Models\User,name',
                'regex:/^[a-z0-9_\.]+$/',
                'min:4'
            ],
            'email' => ['required', 'email', 'unique:App\Models\User'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $new_user = User::query()->create([
            'name' => request('username'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
        ]);

        event(new Registered($new_user));
        Auth::login($new_user);

        return redirect()->route('home');
    }
}
