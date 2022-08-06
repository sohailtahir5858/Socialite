<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        // return 'asd';
        $user = Socialite::driver('google')->user();
        $this->_registerOrLoginUser($user);

        return redirect()->route('home');
    }

    // creating or loging-in the user
    protected function _registerOrLoginUser($data)
    {
        // if email exists then we have to login user
        $user = User::where('email', '=', $data->email)->first();

        if (!$user) {
            $user = new User();
            $user->name = $data->name;
            $user->email = $data->email;
            $user->provider_id = $data->id;
            $user->avatar = $data->avatar;
            $user->save();

            Auth::login($user);
        } else {
            // Mean the user already exists, we just need to login him
            Auth::login($user);
        }
    }
}
