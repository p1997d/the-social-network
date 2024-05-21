<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\SearchService;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    /**
     * Поиск
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function all(Request $request)
    {
        $title = 'Результаты поиска';
        $query = $request->query('query');
        $user = User::find(Auth::id());
        $type = __FUNCTION__;

        $results = SearchService::all($query);

        return view('search.index', compact('title', 'query', 'user', 'results', 'type'));
    }

    /**
     * Поиск людей
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function people(Request $request)
    {
        $title = 'Поиск людей';
        $query = $request->query('query');
        $user = User::find(Auth::id());
        $type = __FUNCTION__;

        $results = [SearchService::people($query)];

        return view('search.index', compact('title', 'query', 'user', 'results', 'type'));
    }

    /**
     * Поиск новостей
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function news(Request $request)
    {
        $title = 'Результаты поиска';
        $query = $request->query('query');
        $user = User::find(Auth::id());
        $type = __FUNCTION__;

        $results = [SearchService::news($query)];

        return view('search.index', compact('title', 'query', 'user', 'results', 'type'));
    }

    /**
     * Поиск групп
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function group(Request $request)
    {
        $title = 'Поиск групп';
        $query = $request->query('query');
        $user = User::find(Auth::id());
        $type = __FUNCTION__;

        $results = [SearchService::group($query)];

        return view('search.index', compact('title', 'query', 'user', 'results', 'type'));
    }

    /**
     * Поиск музыки
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function music(Request $request)
    {
        $title = 'Поиск музыки';
        $query = $request->query('query');
        $user = User::find(Auth::id());
        $type = __FUNCTION__;

        $results = [SearchService::music($query)];

        return view('search.index', compact('title', 'query', 'user', 'results', 'type'));
    }

    /**
     * Поиск видео
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function video(Request $request)
    {
        $title = 'Поиск видео';
        $query = $request->query('query');
        $user = User::find(Auth::id());
        $type = __FUNCTION__;

        $results = [SearchService::video($query)];

        return view('search.index', compact('title', 'query', 'user', 'results', 'type'));
    }
}
