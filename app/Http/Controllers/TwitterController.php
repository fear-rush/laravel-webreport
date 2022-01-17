<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class TwitterController extends Controller
{
    public function twitterRedirect()
    {
        return Socialite::driver('twitter')->redirect();
    }

    public function loginWithTwitter()
    {
        try {
    
            $user = Socialite::driver('twitter')->user();
            // $token = "AAAAAAAAAAAAAAAAAAAAAH8jYAEAAAAAQ%2FP0i%2FjXTradq4JMYuc55rH9g44%3DGz2Kcag08GzDfq4PuSB3ruJ5WvthnsnbIfjzB5Y6hNJDMtwnqX";
            // $secret = "Nl25EwJ8Nsb3OwDLCDu0hu6OhAjHrIBsqL28dfNZkhEdZKvXV2";
            // $user = Socialite::driver('twitter')->userFromTokenAndSecret($token, $secret);
            $isUser = User::where('twitter_id', $user->id)->first();
     
            if($isUser){
                Auth::login($isUser);
                return redirect('home');
            }else{
                $createUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'twitter_id' => $user->id,
                    'password' => encrypt('admin@123')
                ]);
    
                Auth::login($createUser);
                return redirect('home');
            }
    
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }
}