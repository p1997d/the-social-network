<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Main\IndexController as MainIndexController;
use App\Http\Controllers\Main\InfoController as MainInfoController;
use App\Http\Controllers\Main\FriendsController as MainFriendsController;

use App\Http\Controllers\Messenger\IndexController as MessengerIndexController;
use App\Http\Controllers\Messenger\DialogController as MessengerDialogController;
use App\Http\Controllers\Messenger\ChatController as MessengerChatController;

use App\Http\Controllers\Publications\PhotosController as PublicationsPhotosController;
use App\Http\Controllers\Publications\AudiosController as PublicationsAudiosController;
use App\Http\Controllers\Publications\VideosController as PublicationsVideosController;
use App\Http\Controllers\Publications\FilesController as PublicationsFilesController;

Route::group(['namespace' => 'Index'], function () {
    Route::get('/', [MainIndexController::class, 'index'])->name('index');
    Route::get('/id{user}', [MainIndexController::class, 'profile'])->name('profile');
    Route::get('/signup', [MainIndexController::class, 'signup'])->name('auth.signup');
    Route::get('/signin', [MainIndexController::class, 'signin'])->name('auth.signin');

    Route::get('/feed', [MainIndexController::class, 'feed'])->name('feed');
    Route::get('/groups', [MainIndexController::class, 'groups'])->name('groups');
});

Route::group(['namespace' => 'Info'], function () {
    Route::get('/edit', [MainInfoController::class, 'editProfile'])->name('info.editProfile');
    Route::get('/nextLocation', [MainInfoController::class, 'nextLocation'])->name('info.nextLocation');

    Route::post('/updateAvatar', [MainInfoController::class, 'updateAvatar'])->name('info.updateAvatar');
    Route::post('/deleteAvatar', [MainInfoController::class, 'deleteAvatar'])->name('info.deleteAvatar');
    Route::post('/updateProfile', [MainInfoController::class, 'updateProfile'])->name('info.updateProfile');
});

Route::group(['namespace' => 'Friends'], function () {
    Route::get('/friends', [MainFriendsController::class, 'friends'])->name('friends');

    Route::post('/{user}/addFriend', [MainFriendsController::class, 'addFriend'])->name('friends.addFriend');
    Route::post('/{user}/cancelAddFriend', [MainFriendsController::class, 'cancelAddFriend'])->name('friends.cancelAddFriend');
    Route::post('/{user}/approveAddFriend', [MainFriendsController::class, 'approveAddFriend'])->name('friends.approveAddFriend');
    Route::post('/{user}/rejectAddFriend', [MainFriendsController::class, 'rejectAddFriend'])->name('friends.rejectAddFriend');
    Route::post('/{user}/unfriend', [MainFriendsController::class, 'unfriend'])->name('friends.unfriend');
});

Route::group(['namespace' => 'Messenger'], function () {
    Route::get('/messages', [MessengerIndexController::class, 'messages'])->name('messages');
    Route::get('/messages/getMessage', [MessengerIndexController::class, 'getMessage'])->name('messages.getMessage');

    Route::group(['namespace' => 'Dialog'], function () {
        Route::post('/messages/send/{id}', [MessengerDialogController::class, 'create'])->name('messages.send');
        Route::post('/messages/delete/{id}', [MessengerDialogController::class, 'delete'])->name('messages.delete');
        Route::post('/messages/allDelete/{id}', [MessengerDialogController::class, 'allDelete'])->name('messages.allDelete');
        Route::post('/messages/update/{id}', [MessengerDialogController::class, 'update'])->name('messages.update');
        Route::post('/messages/checkRead', [MessengerDialogController::class, 'checkRead'])->name('messages.checkRead');
    });

    Route::group(['namespace' => 'Chat'], function () {
        Route::post('/messages/chat/create', [MessengerChatController::class, 'createChat'])->name('messages.chat.create');
        Route::post('/messages/chat/send/{id}', [MessengerChatController::class, 'create'])->name('messages.chat.send');
        Route::post('/messages/chat/delete/{id}', [MessengerChatController::class, 'delete'])->name('messages.chat.delete');
        Route::post('/messages/chat/update/{id}', [MessengerChatController::class, 'update'])->name('messages.chat.update');
    });
});

Route::group(['namespace' => 'Publications'], function () {
    Route::group(['namespace' => 'Photos'], function () {
        Route::get('/photos', [PublicationsPhotosController::class, 'index'])->name('photos');
        Route::get('/photos/getPhoto', [PublicationsPhotosController::class, 'getPhoto'])->name('getPhoto');

        Route::post('/photos/upload', [PublicationsPhotosController::class, 'upload'])->name('photos.upload');
        Route::post('/photos/delete', [PublicationsPhotosController::class, 'delete'])->name('photos.delete');
    });

    Route::group(['namespace' => 'Audios'], function () {
        Route::get('/audios', [PublicationsAudiosController::class, 'index'])->name('audios');
        Route::get('/audios/getAudio', [PublicationsAudiosController::class, 'getAudio'])->name('getAudio');
        Route::get('/audios/getPlaylist', [PublicationsAudiosController::class, 'getPlaylist'])->name('getPlaylist');
        Route::get('/audios/getLastAudio', [PublicationsAudiosController::class, 'getLastAudio'])->name('getLastAudio');
        Route::get('/audios/download/{id}', [PublicationsAudiosController::class, 'download'])->name('audios.download');

        Route::post('/audios/add', [PublicationsAudiosController::class, 'add'])->name('audios.add');
        Route::post('/audios/upload', [PublicationsAudiosController::class, 'upload'])->name('audios.upload');
        Route::post('/audios/delete', [PublicationsAudiosController::class, 'delete'])->name('audios.delete');

        Route::post('/audios/clearPlaylist', [PublicationsAudiosController::class, 'clearPlaylist'])->name('audios.clearPlaylist');
    });

    Route::group(['namespace' => 'Videos'], function () {
        Route::get('/videos', [PublicationsVideosController::class, 'index'])->name('videos');
        Route::get('/videos/getVideo', [PublicationsVideosController::class, 'getVideo'])->name('getVideo');

        Route::post('/videos/addView', [PublicationsVideosController::class, 'addView'])->name('videos.addView');
        Route::post('/videos/upload', [PublicationsVideosController::class, 'upload'])->name('videos.upload');
        Route::post('/videos/delete', [PublicationsVideosController::class, 'delete'])->name('videos.delete');
    });

    Route::group(['namespace' => 'Files'], function () {
        Route::get('/files/download/{id}', [PublicationsFilesController::class, 'download'])->name('files.download');
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
