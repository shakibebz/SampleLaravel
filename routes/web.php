<?php

use App\Http\Controllers\Api\UrlController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');

});
Route::get('/{short_code}', [UrlController::class, 'redirect'])
    ->where('short_code', '[A-Za-z0-9]{4,32}');

