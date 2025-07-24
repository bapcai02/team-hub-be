<?php

use Illuminate\Support\Facades\Route;
use App\Events\TestBroadcast;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/fire-test', function () {
    broadcast(new TestBroadcast('Hello from Laravel Reverb!'));
    return 'Message broadcasted!';
});