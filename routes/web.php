<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Inventory_Admin\IA_mainController;
use App\Http\Controllers\User\User_mainController;
use App\Http\Controllers\Head_Admin\HA_mainController;
use App\Http\Controllers\Checker_Admin\CA_mainController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});
/* -- Inventory Admin --*/
Route::group(['middleware' => 'loginCheckInventoryAdmin'], function () {
    //Route for Main Controller or Navigation
    Route::controller(IA_mainController::class)->group(function(){
        Route::get('admin/dashboard', 'goToDashboard');
        Route::get('admin/items', 'goToItems');
        Route::get('admin/transaction', 'goToTransactions');
        Route::get('admin/request', 'goToRequest');
        Route::get('admin/report', 'goToReport');
        Route::get('admin/analytic', 'goToAnalytics');
        Route::get('admin/account', 'goToAccount');
        Route::get('admin/audit', 'goToAudit');
        Route::get('admin/profile', 'goToProfile');
    });
});
/* -- USER -- */
Route::group(['middleware' => 'loginCheckUser'], function () {
    //Route for Main Controller or Navigation
    Route::controller(User_mainController::class)->group(function() {
        Route::get('user/dashboard', 'goToDashboard');
        Route::get('user/request', 'goToRequest');
        Route::get('user/history', 'goToHistory');
        Route::get('user/profile', 'goToProfile');
    });
});
/* -- ADMIN HEAD -- */
Route::group(['middleware' => 'loginCheckHeadAdmin'], function () {
    //Route for Main Controller or Navigation
    Route::controller(HA_mainController::class)->group(function(){
        Route::get('head_admin/dashboard', 'goToDashboard');
        Route::get('head_admin/transaction', 'goToTransactions');
        Route::get('head_admin/request', 'goToRequest');
    });
});

/* -- ADMIN CHECKER */
Route::group(['middleware' => 'loginCheckCheckerAdmin'], function () {
    //Route for Main Controller or Navigation
    Route::controller(CA_mainController::class)->group(function(){
        Route::get('checker_admin/dashboard', 'goToDashboard');
        Route::get('checker_admin/transaction', 'goToTransactions');
        Route::get('checker_admin/request', 'goToRequest');
    });
});
