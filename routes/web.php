<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainNavigation\MainNavigationController;
use App\Http\Controllers\Inventory_Admin\IA_mainController;
use App\Http\Controllers\User\User_mainController;
use App\Http\Controllers\Access\LoginController;
use App\Http\Controllers\Inventory_Admin\IA_functionController;
use App\Http\Controllers\Inventory_Admin\Items\CategoryManager;
use App\Http\Controllers\Inventory_Admin\Items\UnitManager;
use App\Http\Controllers\Inventory_Admin\Items\itemManager;
use App\Http\Controllers\Inventory_Admin\Items\InventoryManager;
use App\Http\Controllers\Inventory_Admin\Items\ReceivedManager;
use App\Http\Controllers\Inventory_Admin\Transactions\AdminTransactionManager;
use App\Http\Controllers\Inventory_Admin\Accounts\AccountManager;
use App\Http\Controllers\Inventory_Admin\Pdf\ReportManager;
use App\Http\Controllers\User\Transactions\TransactionsManager;
use App\Http\Controllers\Inventory_Admin\Report\PdfReportManager;
use App\Http\Controllers\Inventory_Admin\Accounts\AdminManager;
use App\Http\Controllers\Inventory_Admin\Charts\ChartManager;
use App\Http\Controllers\Inventory_Admin\Pdf\ReportTransactionManager;
use App\Http\Controllers\Inventory_Admin\Trail\TrailManager;
use App\Http\Controllers\User\User_functionController;
use App\Http\Controllers\User\Account\UserProfileManager;
use Intervention\Image\ImageManagerStatic as Image;
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
    Route::post('/set-selected-admin', 'setSelectedAdmin');
    Route::get('/get-available-admins','getAvailableAdmins');
});
/* -- Inventory Admin --*/
Route::group(['middleware' => 'loginCheckInventoryAdmin'], function () {
    //Route for Main Controller or Navigation
    Route::controller(IA_mainController::class)->group(function(){
        Route::get('admin/', 'goToDashboard');
        Route::get('admin/request', 'goToRequest');
        Route::get('admin/report', 'goToReport');
    });
    Route::controller(CategoryManager::class)->group(function() {
        Route::get('/search-categories', 'searchCategory')->name('search.categories');
        Route::get('/submit-category', 'store')->name('some_route');
        Route::get('/search-edit-categories', 'searchCategory');
        Route::patch('/update-category','updateCategory');
        Route::delete('/delete-category', 'deleteCategory');
        Route::post('/add-category', 'addCategory');
    });
    Route::controller(UnitManager::class)->group(function() {
        Route::get('/search-units', 'searchUnit')->name('search.units');
        Route::get('/submit-unit', 'storeUnit')->name('storeUnit');
        Route::get('/edit-search-units', 'searchUnit');
        Route::patch('/update-unit','updateUnit');
        Route::delete('/delete-unit', 'deleteUnit');
        Route::post('/add-unit','addUnit');
    });
    Route::controller(itemManager::class)->group(function() {
        Route::post('/add-item', 'addItem');
        Route::post('/delete-item', 'deleteItem');
        Route::patch('/update-item', 'editItem');
        Route::get('/admin/refreshItems', 'getItem');
    });
    Route::controller(InventoryManager::class)->group(function() {
        Route::get('/admin/lookup-tables', 'showItems');
    });
    Route::controller(ReceivedManager::class)->group(function(){
        Route::get('/searchItem', 'searchItem')->name('search.itemName');
        Route::get('/submit-item', 'storeItem');
        Route::patch('/received-item', 'receivedItem');
        Route::get('/admin/refreshReceivables', 'refreshReceivables');
        Route::patch('/update-received-item', 'updateReceivedQuantity');
    });
    Route::controller(AdminTransactionManager::class)->group(function(){
        Route::get('admin/transaction', 'goToTransactions');
        Route::patch('/change-transaction-status', 'updateTransactionStatus');
        Route::get('/admin/refreshTransactions', 'getTransactions');
        Route::post('/request-item-admin', 'requestItemAdmin');
        Route::get('/admin/refreshActedTransactions', 'getActedTransactions');
    });
    Route::controller(AccountManager::class)->group(function(){
        Route::get('/admin/account', 'goToAccounts');
        Route::patch('/set-user-role', 'setUserRole');
        Route::patch('/change-user-status', 'changeUserStatus');
    });
    Route::controller(AdminManager::class)->group(function(){
        Route::post('/add-admin', 'addAdmin');
        Route::patch('/update-admin','updateAdmin');
        Route::delete('/delete-admin', 'deleteAdmin');
    });
    Route::controller(ReportManager::class)->group(function(){
        Route::post('/generate-report', 'generateReport');
    });
    Route::controller(ReportTransactionManager::class)->group(function(){
        Route::post('/generate-transaction-report', 'generateTransactionReport');
    });
    Route::controller(PdfReportManager::class)->group(function(){
        Route::post('/add-report', 'addReport');
    });
    Route::controller(ChartManager::class)->group(function(){
        Route::get('/admin/reports', 'goToCharts');
    });
    Route::controller(TrailManager::class)->group(function(){
        Route::get('/admin/trails', 'goToTrails');
    });
    Route::controller(IA_functionController::class)->group(function(){
        Route::get('/logoutAdmin', 'logoutAdmin');
    });
});
/* -- USER -- */
Route::group(['middleware' => 'loginCheckUser'], function () {
    //Route for Main Controller or Navigation
    Route::controller(User_mainController::class)->group(function() {
        Route::get('user/', 'goToDashboard');
    });
    Route::controller(TransactionsManager::class)->group(function() {
        Route::get('user/transactions', 'goToTransactions');
        Route::get('/searchRequestItem', 'searchItem');
        Route::post('/request-item', 'requestItem');
        Route::get('/user/voids', 'goToHistory');
        Route::patch('/user/acceptance-transactions', 'updateTransaction');
        Route::get('/user/refreshTransactions', 'getTransactions');
        Route::patch('/user/cancel-transaction','cancelTransaction');
        Route::get('/user/refreshActedTransactions', 'getActedTransactions');
    });
    Route::controller(UserProfileManager::class)->group(function(){
        Route::get('user/profile', 'goToProfile');
    });
    Route::controller(User_functionController::class)->group(function(){
        Route::get('/logoutUser', 'logoutUser');
    });
});
