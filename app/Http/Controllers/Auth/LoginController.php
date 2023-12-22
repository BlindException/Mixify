<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function redirectToSpotify()
    {
        $scopes = ['playlist-read-private', 'playlist-modify-private', 'playlist-modify-public', 'user-read-playback-state', 'user-modify-playback-state'];
        return Socialite::driver('spotify')
            ->scopes($scopes)
            ->redirect();
    }

    public function handleSpotifyCallback()
    {
        $response = Socialite::driver('spotify')->getAccessTokenResponse(request()->code);
        $user = auth()->user();
        $user->token_expires = Carbon::now()->addSeconds($response['expires_in']);
        $user->access_token = encrypt($response['access_token']);
        $user->refresh_token = encrypt($response['refresh_token']);
        $user->save();
        return Inertia::render('Dashboard');
    }


}
