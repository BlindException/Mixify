<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use SpotifyWebAPI\SpotifyWebAPI;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;


class MixifyController extends Controller
{
    public function index(Request $request)
    {
        $api = $this->checkRefreshSetToken();
        // Make a call to the Spotify API to get the user's playlists
        $playlists = $api->getUserPlaylists($api->me()->id, ['limit' => 50]);
        $playlists = $playlists->items;
        usort($playlists, function ($a, $b) {
            return strcmp($a->name, $b->name);
        });
        $playlists = array_filter($playlists, function ($playlist) {
            return strpos($playlist->name, 'Mixify') === false;
        });
        $playlists = array_values($playlists);
        //get user devices.
        $devices = $api->getMyDevices($api->me()->id)->devices;
        // Render the Mixify/Create.jsx page and pass the JSON-encoded playlists
        return Inertia::render('Mixify/Index', [
            'playlists' => $playlists,
            'devices' => $devices,
        ]);
    }

    public function store(Request $request)
    {
        $selectedLists = $request->input('selectedLists');
        $mixifyList = [];
        foreach ($selectedLists as $playlistId) {
            $tracks = $this->getPlaylistTracks($playlistId);
            foreach ($tracks as $track) {
                if (!in_array($track, $mixifyList)) {
                    $mixifyList[] = $track;
                }
            }
        }
        // Do something with the $mixifyList array, like saving it to the database or returning it as a response
        $this->createPlaylist($mixifyList, $request->input('selectedDevice'));
        return redirect(route('playback.index'));
        //return response()->json($mixifyList);
    }

    private function getPlaylistTracks($playlistId)
    {
        $user = auth()->user();
        $client = new Client();
        $response = $client->get("https://api.spotify.com/v1/playlists/{$playlistId}/tracks", [
            'headers' => [
                'Authorization' => 'Bearer ' . decrypt($user->access_token),
            ],
        ]);
        $tracks = json_decode($response->getBody(), true)['items'];
        return $tracks;
    }

    private function createPlaylist($tracks, $deviceId)
    {
        $user = auth()->user();
        // Use the Spotify API to create a new playlist
        // You'll need to set up the SpotifyWebAPI library and authenticate with Spotify
        $api = new SpotifyWebAPI();
        // Authenticate with Spotify using your credentials
        $api->setAccessToken(decrypt($user->access_token));
        // Get the current datetime
        $now = date('Y-m-d H:i:s');
        $spotifyUser = $api->me();
        // Create a new playlist with the name "Mixify now()"
        $playlistName = "Mixify " . $now;
        $playlist = $api->createPlaylist($spotifyUser->id, ['name' => $playlistName]);
        // Add the tracks to the new playlist
        foreach ($tracks as $track) {
            $api->addPlaylistTracks($playlist->id, [$track['track']['id']]);
        }
        //Start playback
        $api->play($deviceId, [
            'context_uri' => 'spotify:playlist:' . $playlist->id,
        ]);
        $api->shuffle([
            'state' => true,
            'device_id=>$deviceId,'
        ]);
    }

    private function checkRefreshSetToken()
    {
        $user = auth()->user();
        // Check if the user has a valid token
        if ($user->token_expires < now()) {
            $client = new Client();
            $response = $client->post('https://accounts.spotify.com/api/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => decrypt($user->refresh_token),
                    'client_id' => env('SPOTIFY_CLIENT_ID'),
                    'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
                ],
            ]);
            $data = json_decode($response->getBody(), true);
            $user->token_expires = Carbon::now()->addSeconds($data['expires_in']);
            $user->access_token = encrypt($data['access_token']);
            $user->save();
        }
        $api = new SpotifyWebAPI();
        $api->setAccessToken(decrypt($user->access_token));
        return $api;

    }
}