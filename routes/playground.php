<?php

/*
|--------------------------------------------------------------------------
| Test Routes
|--------------------------------------------------------------------------
|
| Here is where you can register test routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "test" middleware group. Enjoy building your test!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('', function () {
    return response()->json(['code' => \Symfony\Component\HttpFoundation\Response::HTTP_OK,
        'message' => 'Welcome to your playground!',
    ]);
});
