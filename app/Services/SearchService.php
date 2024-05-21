<?php

namespace App\Services;

use App\Models\User;
use App\Models\Group;
use App\Models\Audio;
use App\Models\Video;
use Illuminate\Support\Facades\Crypt;

class SearchService
{
    public $title, $items, $link, $linkTitle, $template;

    public function __construct($title, $items, $link, $linkTitle, $template)
    {
        $this->title = $title;
        $this->items = $items;
        $this->link = $link;
        $this->linkTitle = $linkTitle;
        $this->template = $template;
    }

    public static function people($query, $quantity = null)
    {
        $users = User::where('firstname', 'like', '%' . $query . '%')
            ->orWhere('surname', 'like', '%' . $query . '%')->get();

        if ($quantity) {
            $users = $users->take($quantity);
        }

        return new self('Люди', $users, 'search.people', 'Показать всех', 'search.layouts.people');
    }

    public static function news($query, $quantity = null)
    {
        $news = PostService::getNews()->filter(function ($item) use ($query) {
            return str_contains($item['postDecryptContent'], $query);
        });

        if ($quantity) {
            $news = $news->take($quantity);
        }

        return new self('Новости', $news, 'search.news', 'Показать все', 'search.layouts.news');
    }

    public static function group($query, $quantity = null)
    {
        $groups = Group::where('title', 'like', '%' . $query . '%')->get();

        if ($quantity) {
            $groups = $groups->take($quantity);
        }

        return new self('Группы', $groups, 'search.group', 'Показать все', 'search.layouts.groups');
    }

    public static function music($query, $quantity = null)
    {
        $audios = Audio::where([
            ['title', '!=', ''],
            ['title', 'like', '%' . $query . '%'],
            ['deleted_at', null]
        ])->orWhere([
                    ['artist', '!=', ''],
                    ['artist', 'like', '%' . $query . '%'],
                    ['deleted_at', null]
                ])->get();

        if ($quantity) {
            $audios = $audios->take($quantity);
        }

        return new self('Музыка', $audios, 'search.music', 'Показать все', 'search.layouts.music');
    }

    public static function video($query, $quantity = null)
    {
        $videos = Video::where([
            ['title', '!=', ''],
            ['title', 'like', '%' . $query . '%'],
            ['deleted_at', null]
        ])->get();

        if ($quantity) {
            $videos = $videos->take($quantity);
        }

        return new self('Видео', $videos, 'search.video', 'Показать все', 'search.layouts.video');
    }

    public static function all($query)
    {
        $people = self::people($query, 3);
        $news = self::news($query, 3);
        $groups = self::group($query, 3);
        $music = self::music($query, 3);
        $video = self::video($query, 3);

        return array_filter(array($people, $news, $groups, $music, $video), function ($item) {
            return $item->items->count() > 0;
        });
    }


}
