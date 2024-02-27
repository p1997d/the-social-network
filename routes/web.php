<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::group(['namespace' => 'Index'], function () {
    Route::get('/', [App\Http\Controllers\Main\IndexController::class, 'index'])->name('index');
    Route::get('/id{user}', [App\Http\Controllers\Main\IndexController::class, 'profile'])->name('profile');
    // Route::get('/mypage', function () {
    //     return redirect()->route('profile', Auth::id());
    // })->name('mypage');
    Route::get('/signup', [App\Http\Controllers\Main\IndexController::class, 'signup'])->name('auth.signup');
    Route::get('/signin', [App\Http\Controllers\Main\IndexController::class, 'signin'])->name('auth.signin');
});

Route::group(['namespace' => 'Info'], function () {
    Route::post('/updateavatar', [App\Http\Controllers\Main\InfoController::class, 'updateAvatar'])->name('info.updateavatar');
    Route::post('/deleteavatar', [App\Http\Controllers\Main\InfoController::class, 'deleteAvatar'])->name('info.deleteavatar');
    Route::get('/edit', [App\Http\Controllers\Main\InfoController::class, 'editProfile'])->name('info.editprofile');
    Route::post('/updateprofile', [App\Http\Controllers\Main\InfoController::class, 'updateProfile'])->name('info.updateprofile');
    Route::get('/nextlocation', [App\Http\Controllers\Main\InfoController::class, 'nextLocation'])->name('info.nextlocation');
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
