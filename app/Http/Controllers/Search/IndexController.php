<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\SearchService;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Результаты поиска';
        $query = $request->query('query');
        $user = User::find(Auth::id());

        $results = SearchService::all($query);

        return view('search.index', compact('title', 'query', 'user', 'results'));
    }

    public function people(Request $request)
    {
        $title = 'Поиск людей';
        $query = $request->query('query');
        $user = User::find(Auth::id());

        $results = [SearchService::people($query)];

        return view('search.index', compact('title', 'query', 'user', 'results'));
    }

    public function news(Request $request)
    {
        $title = 'Результаты поиска';
        $query = $request->query('query');
        $user = User::find(Auth::id());

        $results = [SearchService::news($query)];

        return view('search.index', compact('title', 'query', 'user', 'results'));
    }

    public function group(Request $request)
    {
        $title = 'Поиск групп';
        $query = $request->query('query');
        $user = User::find(Auth::id());

        $results = [SearchService::group($query)];

        return view('search.index', compact('title', 'query', 'user', 'results'));
    }

    public function music(Request $request)
    {
        $title = 'Поиск музыки';
        $query = $request->query('query');
        $user = User::find(Auth::id());

        $results = [SearchService::music($query)];

        return view('search.index', compact('title', 'query', 'user', 'results'));
    }

    public function video(Request $request)
    {
        $title = 'Поиск видео';
        $query = $request->query('query');
        $user = User::find(Auth::id());

        $results = [SearchService::video($query)];

        return view('search.index', compact('title', 'query', 'user', 'results'));
    }
}
