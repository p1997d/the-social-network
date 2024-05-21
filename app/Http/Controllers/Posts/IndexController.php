<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    /**
     * Отображает страницу с постом
     *
     * @param integer $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return view('main.info', ['title' => 'Ошибка', 'info' => 'Запись не найдена.']);
        }

        $data = PostService::getPost($post);
        $data['title'] = "Запись со стены";
        $data['user'] = User::find(Auth::id());

        return view('main.post', $data);
    }

    /**
     * Создает новый пост
     *
     * @param Request $request
     * @return array
     */
    public function create(Request $request)
    {
        if (!$request->user && !$request->group) {
            abort(404);
        }

        $request->validate([
            'content' => 'required_without:attachments|max:2000',
            'attachments' => 'required_without:content',
        ]);

        $decryptContent = $request->content;
        $content = Crypt::encrypt($decryptContent);

        $post = PostService::create($content);

        if ($request->user) {
            $user = User::find($request->user);
            PostService::saveForUser($post, $user);
        } else if ($request->group) {
            $group = Group::find($request->group);
            PostService::saveForGroup($post, $group);
        }

        $attachments = PostService::saveAttachments(request()->attachments, $post);

        return PostService::getPost($post);
    }

    /**
     * Удаляет пост
     *
     * @param integer $id
     * @return array
     */
    public function delete($id)
    {
        $post = Post::find($id);
        $post->update([
            'deleted_at' => now(),
        ]);

        return ['status' => 'success'];
    }
}
