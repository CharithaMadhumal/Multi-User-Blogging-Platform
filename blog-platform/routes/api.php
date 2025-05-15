<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/store',[
    PostController::class,'store'
]);

Route::post('/add',[
    CommentController::class,'add'
]);

Route::delete('/delete',[
    CommentController::class,'delete'
]);

Route::post('/input',[
    UserController::class,'input'
]);

Route::put('/update',[
    PostController::class,'update'
]);

Route::delete('/delete',[
    PostController::class,'delete'
]);


Route::post('/add',[
    LikeController::class,'add'
]);

Route::post('/add',[
    TagController::class,'add'
]);

Route::delete('/tags/{id}',[
    TagController::class,'delete'
]);

Route::get('/auth/redirect/{provider}', [SocialController::class, 'redirect']);
Route::get('/auth/callback/{provider}', [SocialController::class, 'callback']);
