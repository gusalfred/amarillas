<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\SocialAccountService;
use Socialite; // socialite namespace

class SocialAuthController extends Controller
{
    // redirect function
    public function redirect($provider){
        return Socialite::driver($provider)->redirect();
    }
    // callback function
    public function callback(SocialAccountService $service, $provider){
        // when facebook call us a with token
        // we need to change this method too
        $user = $service->createOrGetUser(Socialite::driver($provider));
        auth()->login($user);
        return redirect()->to('/home');
    }
}
