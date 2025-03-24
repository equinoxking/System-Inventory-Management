<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainNavigation\MainNavigationController;
use App\Http\Controllers\Inventory_Admin\IA_mainController;
use App\Http\Controllers\User\User_mainController;
use App\Http\Controllers\Head_Admin\HA_mainController;
use App\Http\Controllers\Checker_Admin\CA_mainController;
use App\Http\Controllers\Access\RegisterController;
use App\Http\Controllers\Access\LoginController;
use App\Http\Controllers\Inventory_Admin\IA_functionController;
use App\Http\Controllers\Inventory_Admin\Items\CategoryManager;
use App\Http\Controllers\Inventory_Admin\Items\UnitManager;
use App\Http\Controllers\Inventory_Admin\Items\itemManager;
use App\Http\Controllers\Inventory_Admin\Items\InventoryManager;
use App\Http\Controllers\Inventory_Admin\Items\ReceivedManager;
use App\Http\Controllers\Inventory_Admin\Accounts\AccountManager;
use App\Http\Controllers\Inventory_Admin\Pdf\ReportManager;

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
//Route for Main Navigation
Route::controller(MainNavigationController::class)->group(function() {
    Route::get('/', 'goToIndex');
});
//Route for Access
Route::controller(LoginController::class)->group(function(){
    Route::post('/login-user', 'login');
});
/* -- Inventory Admin --*/
Route::group(['middleware' => 'loginCheckInventoryAdmin'], function () {
    //Route for Main Controller or Navigation
    Route::controller(IA_mainController::class)->group(function(){
        Route::get('admin/', 'goToDashboard');
        Route::get('admin/transaction', 'goToTransactions');
        Route::get('admin/request', 'goToRequest');
        Route::get('admin/report', 'goToReport');
        Route::get('admin/audit', 'goToAudits');
        Route::get('admin/profile', 'goToProfile');
    });
    Route::controller(CategoryManager::class)->group(function() {
        Route::get('/search-categories', 'searchCategory')->name('search.categories');
        Route::get('/submit-category', 'store')->name('some_route');
        Route::get('/search-edit-categories', 'searchCategory');

    });
    Route::controller(UnitManager::class)->group(function() {
        Route::get('/search-units', 'searchUnit')->name('search.units');
        Route::get('/submit-unit', 'storeUnit')->name('storeUnit');
        Route::get('/edit-search-units', 'searchUnit');
    });
    Route::controller(itemManager::class)->group(function() {
        Route::post('/add-item', 'addItem');
        Route::post('/delete-item', 'deleteItem');
        Route::patch('/update-item', 'editItem');
    });
    Route::controller(InventoryManager::class)->group(function() {
        Route::get('admin/items/view-items', 'showItems');
    });
    Route::controller(ReceivedManager::class)->group(function(){
        Route::get('/searchItem', 'searchItem')->name('search.itemName');
        Route::get('/submit-item', 'storeItem');
        Route::patch('/received-item', 'receivedItem');
    });
    Route::controller(AccountManager::class)->group(function(){
        Route::get('/admin/account', 'goToAccounts');
        Route::patch('/set-user-role', 'setUserRole');
        Route::patch('/change-user-status', 'changeUserStatus');
    });
    Route::controller(ReportManager::class)->group(function(){
        Route::post('/generate-report', 'generateReport');
    });
    Route::controller(IA_functionController::class)->group(function() {
        Route::get('logoutAdmin', 'logoutAdmin');
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
