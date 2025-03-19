<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Access\RegisterController;
use App\Http\Controllers\Access\LoginController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
    
});
Route::post('/register-user', [RegisterController::class, 'registration'])->name('register-user');

Route::controller(LoginController::class)->group(function(){
    Route::post('/login-user', 'loginUser');
});