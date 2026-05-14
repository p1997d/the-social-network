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
     * @return \Illuminate\Contracts\Support\Renderable | \Illuminate\Http\RedirectResponse
     */
    public function feed(Request $request)
    {
        $title = 'Новости';

        if (Auth::guest()) {
            return redirect()->route('login');
        }

        $section = $request['section'];
        $posts = $this->getNews($request);

        return view('main.feed', compact('title', 'posts', 'section'));
    }

    public function getNews(Request $request)
    {
        $section = $request['section'];

        return match ($section) {
            default => PostService::getNews($request->page),
            'likes' => PostService::getLikes($request->page),
        };
    }
}
