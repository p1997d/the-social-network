<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::group(['namespace' => 'Index'], function () {
    Route::get('/', [App\Http\Controllers\Main\IndexController::class, 'index'])->name('index');
    Route::get('/id{user}', [App\Http\Controllers\Main\IndexController::class, 'profile'])->name('profile');
    Route::get('/signup', [App\Http\Controllers\Main\IndexController::class, 'signup'])->name('auth.signup');
    Route::get('/signin', [App\Http\Controllers\Main\IndexController::class, 'signin'])->name('auth.signin');

    Route::get('/feed', [App\Http\Controllers\Main\IndexController::class, 'feed'])->name('feed');
    Route::get('/groups', [App\Http\Controllers\Main\IndexController::class, 'groups'])->name('groups');
});

Route::group(['namespace' => 'Info'], function () {
    Route::get('/edit', [App\Http\Controllers\Main\InfoController::class, 'editProfile'])->name('info.editProfile');
    Route::get('/nextLocation', [App\Http\Controllers\Main\InfoController::class, 'nextLocation'])->name('info.nextLocation');

    Route::post('/updateAvatar', [App\Http\Controllers\Main\InfoController::class, 'updateAvatar'])->name('info.updateAvatar');
    Route::post('/deleteAvatar', [App\Http\Controllers\Main\InfoController::class, 'deleteAvatar'])->name('info.deleteAvatar');
    Route::post('/updateProfile', [App\Http\Controllers\Main\InfoController::class, 'updateProfile'])->name('info.updateProfile');
});

Route::group(['namespace' => 'Friends'], function () {
    Route::get('/friends', [App\Http\Controllers\Main\FriendsController::class, 'friends'])->name('friends');

    Route::post('/{user}/addFriend', [App\Http\Controllers\Main\FriendsController::class, 'addFriend'])->name('friends.addFriend');
    Route::post('/{user}/cancelAddFriend', [App\Http\Controllers\Main\FriendsController::class, 'cancelAddFriend'])->name('friends.cancelAddFriend');
    Route::post('/{user}/approveAddFriend', [App\Http\Controllers\Main\FriendsController::class, 'approveAddFriend'])->name('friends.approveAddFriend');
    Route::post('/{user}/rejectAddFriend', [App\Http\Controllers\Main\FriendsController::class, 'rejectAddFriend'])->name('friends.rejectAddFriend');
    Route::post('/{user}/unfriend', [App\Http\Controllers\Main\FriendsController::class, 'unfriend'])->name('friends.unfriend');
});

Route::group(['namespace' => 'Messenger'], function () {
    Route::get('/messages', [App\Http\Controllers\Messanger\IndexController::class, 'messages'])->name('messages');
    Route::get('/messages/getMessage', [App\Http\Controllers\Messanger\IndexController::class, 'getMessage'])->name('messages.getMessage');

    Route::group(['namespace' => 'Dialog'], function () {
        Route::post('/messages/send/{id}', [App\Http\Controllers\Messanger\DialogController::class, 'create'])->name('messages.send');
        Route::post('/messages/delete/{id}', [App\Http\Controllers\Messanger\DialogController::class, 'delete'])->name('messages.delete');
        Route::post('/messages/allDelete/{id}', [App\Http\Controllers\Messanger\DialogController::class, 'allDelete'])->name('messages.allDelete');
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
        Route::get('/photos/getPhoto', [App\Http\Controllers\Publications\PhotosController::class, 'getPhoto'])->name('getPhoto');

        Route::post('/photos/upload', [App\Http\Controllers\Publications\PhotosController::class, 'upload'])->name('photos.upload');
        Route::post('/photos/delete', [App\Http\Controllers\Publications\PhotosController::class, 'delete'])->name('photos.delete');
    });

    Route::group(['namespace' => 'Audios'], function () {
        Route::get('/audios', [App\Http\Controllers\Publications\AudiosController::class, 'index'])->name('audios');
        Route::get('/audios/getAudio', [App\Http\Controllers\Publications\AudiosController::class, 'getAudio'])->name('getAudio');
        Route::get('/audios/getPlaylist', [App\Http\Controllers\Publications\AudiosController::class, 'getPlaylist'])->name('getPlaylist');
        Route::get('/audios/getLastAudio', [App\Http\Controllers\Publications\AudiosController::class, 'getLastAudio'])->name('getLastAudio');

        Route::post('/audios/add', [App\Http\Controllers\Publications\AudiosController::class, 'add'])->name('audios.add');
        Route::post('/audios/upload', [App\Http\Controllers\Publications\AudiosController::class, 'upload'])->name('audios.upload');
        Route::post('/audios/download', [App\Http\Controllers\Publications\AudiosController::class, 'download'])->name('audios.download');
        Route::post('/audios/delete', [App\Http\Controllers\Publications\AudiosController::class, 'delete'])->name('audios.delete');

        Route::post('/audios/clearPlaylist', [App\Http\Controllers\Publications\AudiosController::class, 'clearPlaylist'])->name('audios.clearPlaylist');
    });

    Route::group(['namespace' => 'Videos'], function () {
        Route::get('/videos', [App\Http\Controllers\Publications\VideosController::class, 'index'])->name('videos');

        Route::post('/videos/upload', [App\Http\Controllers\Publications\VideosController::class, 'upload'])->name('videos.upload');
        Route::post('/videos/delete', [App\Http\Controllers\Publications\VideosController::class, 'delete'])->name('videos.delete');
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
