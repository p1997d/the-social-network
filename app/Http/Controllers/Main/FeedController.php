<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\PostService;

class FeedController extends Controller
{

    /**
     * Отображает страницу новости
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function feed(Request $request)
    {
        $title = 'Новости';

        if (Auth::guest()) {
            return redirect()->route('auth.signin');
        }

        $section = $request->query('section');

        $posts = match ($section) {
            default => PostService::getNews(),
            'likes' => PostService::getLikes(),
        };

        return view('main.feed', compact('title', 'posts', 'section'));
    }

    public function getNews(Request $request)
    {
        return match ($request->section) {
            default => PostService::getNews($request->page),
            'likes' => PostService::getLikes($request->page),
        };
    }
}
