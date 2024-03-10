<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::group(['namespace' => 'Index'], function () {
    Route::get('/', [App\Http\Controllers\Main\IndexController::class, 'index'])->name('index');
    Route::get('/id{user}', [App\Http\Controllers\Main\IndexController::class, 'profile'])->name('profile');
    Route::get('/signup', [App\Http\Controllers\Main\IndexController::class, 'signup'])->name('auth.signup');
    Route::get('/signin', [App\Http\Controllers\Main\IndexController::class, 'signin'])->name('auth.signin');

    // Route::get('/mypage', function () {
    //     return redirect()->route('profile', Auth::id());
    // })->name('mypage');
});

Route::group(['namespace' => 'Info'], function () {
    Route::get('/edit', [App\Http\Controllers\Main\InfoController::class, 'editProfile'])->name('info.editprofile');
    Route::get('/nextlocation', [App\Http\Controllers\Main\InfoController::class, 'nextLocation'])->name('info.nextlocation');

    Route::post('/updateavatar', [App\Http\Controllers\Main\InfoController::class, 'updateAvatar'])->name('info.updateavatar');
    Route::post('/deleteavatar', [App\Http\Controllers\Main\InfoController::class, 'deleteAvatar'])->name('info.deleteavatar');
    Route::post('/updateprofile', [App\Http\Controllers\Main\InfoController::class, 'updateProfile'])->name('info.updateprofile');
});

Route::group(['namespace' => 'Friends'], function () {
    Route::get('/friends', [App\Http\Controllers\Main\FriendsController::class, 'friends'])->name('friends');

    Route::post('/{user}/addfriend', [App\Http\Controllers\Main\FriendsController::class, 'addFriend'])->name('friends.addfriend');
    Route::post('/{user}/canceladdfriend', [App\Http\Controllers\Main\FriendsController::class, 'cancelAddFriend'])->name('friends.canceladdfriend');
    Route::post('/{user}/approveaddfriend', [App\Http\Controllers\Main\FriendsController::class, 'approveAddFriend'])->name('friends.approveaddfriend');
    Route::post('/{user}/rejectaddfriend', [App\Http\Controllers\Main\FriendsController::class, 'rejectAddFriend'])->name('friends.rejectaddfriend');
    Route::post('/{user}/unfriend', [App\Http\Controllers\Main\FriendsController::class, 'unfriend'])->name('friends.unfriend');
});

Route::group(['namespace' => 'Messenger'], function () {
    Route::get('/messages', [App\Http\Controllers\Messanger\IndexController::class, 'messages'])->name('messages');
    Route::get('/messages/getMessage', [App\Http\Controllers\Messanger\IndexController::class, 'getMessage'])->name('messages.getMessage');

    Route::group(['namespace' => 'Dialog'], function () {
        Route::post('/messages/send/{id}', [App\Http\Controllers\Messanger\DialogController::class, 'create'])->name('messages.send');
        Route::post('/messages/delete/{id}', [App\Http\Controllers\Messanger\DialogController::class, 'delete'])->name('messages.delete');
        Route::post('/messages/alldelete/{id}', [App\Http\Controllers\Messanger\DialogController::class, 'allDelete'])->name('messages.alldelete');
        Route::post('/messages/update/{id}', [App\Http\Controllers\Messanger\DialogController::class, 'update'])->name('messages.update');
        Route::post('/messages/checkRead', [App\Http\Controllers\Messanger\DialogController::class, 'checkRead'])->name('messages.checkRead');
    });

    Route::group(['namespace' => 'Chat'], function () {
        Route::post('/messages/chat/create', [App\Http\Controllers\Messanger\ChatController::class, 'createChat'])->name('messages.chat.create');
        Route::post('/messages/chat/send/{id}', [App\Http\Controllers\Messanger\ChatController::class, 'create'])->name('messages.chat.send');
        Route::post('/messages/chat/delete/{id}', [App\Http\Controllers\Messanger\ChatController::class, 'delete'])->name('messages.chat.delete');
        Route::post('/messages/chat/update/{id}', [App\Http\Controllers\Messanger\ChatController::class, 'update'])->name('messages.chat.update');
    });
});

Route::group(['namespace' => 'Publications'], function () {
    Route::group(['namespace' => 'Photos'], function () {
        Route::get('/photos', [App\Http\Controllers\Publications\PhotosController::class, 'index'])->name('photos');
        Route::get('/publications/getPhoto', [App\Http\Controllers\Publications\PhotosController::class, 'getPhoto'])->name('getPhoto');

        Route::post('/photos/upload', [App\Http\Controllers\Publications\PhotosController::class, 'upload'])->name('photos.upload');
        Route::post('/photos/delete', [App\Http\Controllers\Publications\PhotosController::class, 'delete'])->name('photos.delete');
    });

    Route::group(['namespace' => 'Audios'], function () {
        Route::get('/audios', [App\Http\Controllers\Publications\AudiosController::class, 'index'])->name('audios');

        Route::post('/audios/upload', [App\Http\Controllers\Publications\AudiosController::class, 'upload'])->name('audios.upload');
        Route::post('/audios/delete', [App\Http\Controllers\Publications\AudiosController::class, 'delete'])->name('audios.delete');
    });

    Route::group(['namespace' => 'Videos'], function () {
        Route::get('/videos', [App\Http\Controllers\Publications\VideosController::class, 'index'])->name('videos');

        Route::post('/videos/upload', [App\Http\Controllers\Publications\VideosController::class, 'upload'])->name('videos.upload');
        Route::post('/videos/delete', [App\Http\Controllers\Publications\VideosController::class, 'delete'])->name('videos.delete');
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
