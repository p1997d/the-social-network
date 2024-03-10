<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\UserService;
use App\Services\GeneralService;
use App\Services\PublicationsService;

class AudiosController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->query('id');

        list($title, $user) = GeneralService::getTitleAndUser($id, 'Аудиозаписи');

        return view('publications.audios.index', compact('title', 'user'));
    }
}
