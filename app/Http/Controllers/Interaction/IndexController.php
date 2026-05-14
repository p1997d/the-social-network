<?php

namespace App\Http\Controllers\Interaction;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Like;
use App\Services\InteractionService;
use App\Services\GeneralService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;



class IndexController extends Controller
{
    /**
     * Добавляет или удаляет лайк
     *
     * @param Request $request
     * @return array
     */
    public function like(Request $request)
    {
        $user = User::find(Auth::id());

        $type = GeneralService::getType($request['type']) ?? abort(422);
        $model = $type::find($request['id']);

        if (!$user) {
            abort(403);
        }

        if (!$model) {
            abort(404);
        }

        $like = Like::where([
            ['user', $user->id],
            ['likeable_id', $request->id],
            ['likeable_type', $type],
        ]);

        if ($like->exists()) {
            $like->delete();

            $class = 'btn btn-sm btn-outline-secondary';
        } else {
            $like = new Like();
            $like->user = $user->id;
            $like->likeable_id = $request->id;
            $like->likeable_type = $type;

            $like->save();

            $class = 'btn btn-sm btn-outline-danger active';
        }

        return [
            'countLikes' => $model->likes->count(),
            'class' => $class,
        ];
    }

    public function commentCreate(Request $request)
    {
        $type = GeneralService::getType($request['type']) ?? abort(422);

        $comment = new Comment();
        $user = User::find(Auth::id());

        $content = Crypt::encrypt($request['content']);

        $comment['content'] = $content;
        $comment['commentable_id'] = $request['id'];
        $comment['commentable_type'] = $type;
        $comment['author'] = $user['id'];
        $comment->save();

        return back();
    }

    public function commentDelete(Request $request)
    {
        if (!Auth::check())
            abort(403);
        
        $comment = Comment::find($request['id']);

        if ($comment['author'] != Auth::id() && $comment->commentable->author != Auth::id())
            abort(403);

        $comment->delete();

        return back();
    }

    public function share(Request $request)
    {
        return InteractionService::share($request);
    }

    public function getComment(Request $request)
    {
        $page = $request->page;
        $modelType = $request->type;
        $model = $modelType::find($request->id);

        return InteractionService::getComments($model, $model->group ?? null)->forPage($page, 25);
    }
}
