<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Audio;
use App\Models\Playlist;

use App\Services\FileService;
use App\Services\AudioService;
use App\Services\GeneralService;

use function PHPUnit\Framework\returnSelf;

class AudiosController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::guest()) {
            return redirect()->route('auth.signin');
        }

        $id = $request->query('id');

        list($title, $user) = GeneralService::getTitleAndUser($id, "Аудиозаписи");

        $playlist = $user->playlist;
        $audios = AudioService::getAudios($playlist);

        return view('publications.audios.index', compact('title', 'user', 'audios', 'playlist'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            // 'audios' => 'required|mimes:mp3|max:204800',
            'audios' => 'required|max:204800',
        ]);

        $user = User::find(Auth::id());
        $file = FileService::create($user, 'uploaded', time(), $request->audios);
        $data = AudioService::create($user, $file, $request->title, $request->artist);

        return $data;
    }

    public function delete(Request $request)
    {
        return AudioService::delete($request->audio);
    }

    public function download(Request $request)
    {
        $path = storage_path("app/public/files/{$request->file}");

        if (file_exists($path)) {
            return response()->download($path);
        } else {
            abort(404, 'File not found');
        }
    }

    public function add(Request $request)
    {
        return AudioService::add($request->audio);
    }

    public function getAudio(Request $request)
    {
        return AudioService::getAudio($request->id, $request->playlist);
    }

    public function getPlaylist(Request $request)
    {
        return AudioService::getPlaylist($request->playlist);
    }

    public function getLastAudio()
    {
        return AudioService::getLastAudio();
    }

    public function clearPlaylist()
    {
        $user = User::find(Auth::id());
        $user->currentPlaylist->delete();
    }
}
