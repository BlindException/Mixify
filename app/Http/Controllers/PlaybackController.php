<?php

namespace App\Http\Controllers;

use stdClass;
use Exception;
use Carbon\Carbon;
use Inertia\Inertia;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use SpotifyWebAPI\SpotifyWebAPI;
use Illuminate\Support\Facades\Http;

class PlaybackController extends Controller
{
    public function index()
    {
        $api = $this->checkRefreshSetToken();
        $currentTrack = $api->getMyCurrentTrack();
        $artists = [];
        foreach ($currentTrack->item->artists as $artist) {
            array_push($artists, $artist->name);
        }
        $trackName = $currentTrack->item->name;
        return Inertia::render('Playback/Index', [
            'artists' => $artists,
            'name' => $trackName,
        ]);
    }
    public function play()
    {
        $api = $this->checkRefreshSetToken();
        $api->play();
        $currentTrack = $api->getMyCurrentTrack();
        $artists = [];
        foreach ($currentTrack->item->artists as $artist) {
            array_push($artists, $artist->name);
        }
        $trackName = $currentTrack->item->name;
        return response()->json([
            'artists' => $artists,
            'name' => $trackName,
        ]);
    }
    public function pause()
    {
        $api = $this->checkRefreshSetToken();
        $api->pause();
        $currentTrack = $api->getMyCurrentTrack();
        $artists = [];
        foreach ($currentTrack->item->artists as $artist) {
            array_push($artists, $artist->name);
        }
        $trackName = $currentTrack->item->name;
        return response()->json([
            'artists' => $artists,
            'name' => $trackName,
        ]);
    }
    public function next()
    {
        $api = $this->checkRefreshSetToken();
        $api->next();
        sleep(1);
        $currentTrack = $api->getMyCurrentTrack();
        $artists = [];
        foreach ($currentTrack->item->artists as $artist) {
            array_push($artists, $artist->name);
        }
        $trackName = $currentTrack->item->name;
        return response()->json([
            'artists' => $artists,
            'name' => $trackName,
        ]);
    }

    public function previous()
    {
        $api = $this->checkRefreshSetToken();
        $api->previous();
        $currentTrack = $api->getMyCurrentTrack();
        $artists = [];
        foreach ($currentTrack->item->artists as $artist) {
            array_push($artists, $artist->name);
        }
        $trackName = $currentTrack->item->name;
        return response()->json([
            'artists' => $artists,
            'name' => $trackName,
        ]);
    }
    public function destroy()
    {
        $api = $this->checkRefreshSetToken();
        $currentTrack = $api->getMyCurrentTrack();
        $playlistId = str_replace("spotify:playlist:", "", $currentTrack->context->uri);
        $tracks = $this->getPlaylistTracks($playlistId);
        $trackURIs = [];
        $chunks = array_chunk($trackURIs, 99);
        try {
            foreach ($chunks as $chunk) {
                $api->deletePlaylistTracks($playlistId, ['tracks' => $chunk]);
            }
            $api->pause();
            $api->unfollowPlaylist($playlistId);
            return response()->json(['message' => 'Mixify Mix deleted.'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error deleting Mixify tracks.'], 500);
        }
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
}