<?php

use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function(){;
    Route::prefix('user')->controller(UserController::class)->group(function(){
        Route::get('/connected','getUser');
        Route::post('/logout','logout');
    });//Logout, userConnected
});

Route::prefix('user')->controller(UserController::class)->group(function(){
    Route::post('register','store');
    Route::post('login','login');
    Route::post('password-code/send','generateTwoFacteurUserCode');
    Route::post('email','emailExist');
    Route::put("password/update",'setPassword');
});// Register, Login

Route::prefix('password-code')->controller(TwoFactorController::class)->group(function(){
    Route::post('verify', 'store');
    Route::post('resend', 'resend');
});//verify and sen restore code
