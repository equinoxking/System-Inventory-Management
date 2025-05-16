<?php
//Laravel Packages
use Illuminate\Support\Facades\Route;
//Controller List
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
use App\Http\Controllers\Inventory_Admin\Pdf\ReportTransactionManager;
use App\Http\Controllers\Inventory_Admin\Items\SupplierManager;
use App\Http\Controllers\Inventory_Admin\Trail\TrailManager;
use App\Http\Controllers\Inventory_Admin\Dashboard\DashboardAccountManager;
use App\Http\Controllers\User\Reports\UserReportManager;
use App\Http\Controllers\User\User_functionController;
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
//Route for Main Navigation Controller
Route::controller(MainNavigationController::class)->group(function() {
    Route::get('/', 'goToIndex');
});
//Route for Access Controller
Route::controller(LoginController::class)->group(function(){
    Route::post('/login-user', 'login');
    Route::post('/set-admin-session', 'setAdminSession');
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
    //Route for Category Controller 
    Route::controller(CategoryManager::class)->group(function() {
        Route::get('/search-categories', 'searchCategory')->name('search.categories');
        Route::get('/submit-category', 'store')->name('some_route');
        Route::get('/search-edit-categories', 'searchCategory');
        Route::patch('/update-category','updateCategory');
        Route::delete('/delete-category', 'deleteCategory');
        Route::post('/add-category', 'addCategory');
        Route::get('/get-category-control-number/{id}', 'getControlNumber');
    });
    //Route for Unit Controller 
    Route::controller(UnitManager::class)->group(function() {
        Route::get('/search-units', 'searchUnit')->name('search.units');
        Route::get('/submit-unit', 'storeUnit')->name('storeUnit');
        Route::get('/edit-search-units', 'searchUnit');
        Route::patch('/update-unit','updateUnit');
        Route::delete('/delete-unit', 'deleteUnit');
        Route::post('/add-unit','addUnit');
        Route::get('/get-unit-control-number/{id}', 'getControlNumber');
    });
    //Route for Item Controller 
    Route::controller(itemManager::class)->group(function() {
        Route::post('/add-item', 'addItem');
        Route::post('/delete-item', 'deleteItem');
        Route::patch('/update-item', 'editItem');
        Route::get('/admin/refreshItems', 'getItem');
    });
    //Route for Inventory Controller 
    Route::controller(InventoryManager::class)->group(function() {
        Route::get('/admin/lookup-tables/items', 'showItems');
        Route::get('/admin/lookup-tables/deliveries', 'showDeliveries');
        Route::get('/admin/lookup-tables/categories','showCategories');
        Route::get('/admin/lookup-tables/units','showUnits');
        Route::get('/admin/lookup-tables/user-accounts','showAccounts');
        Route::get('/admin/lookup-tables/suppliers','showSuppliers');
    });
    //Route for Delivery Controller 
    Route::controller(ReceivedManager::class)->group(function(){
        Route::get('/searchItem', 'searchItem')->name('search.itemName');
        Route::get('/submit-item', 'storeItem');
        Route::patch('/received-item', 'receivedItem');
        Route::get('/admin/refreshReceivables', 'refreshReceivables');
        Route::patch('/update-received-item', 'updateReceivedQuantity');
    });
    //Route for Transaction Controller 
    Route::controller(AdminTransactionManager::class)->group(function(){
        Route::get('admin/transaction', 'goToTransactions');
        Route::patch('/change-transaction-status', 'updateTransactionStatus');
        Route::get('/admin/refreshTransactions', 'getTransactions');
        Route::post('/request-item-admin', 'requestItemAdmin');
        Route::get('/admin/refreshActedTransactions', 'getActedTransactions');
    });
    //Route for Account Controller 
    Route::controller(AccountManager::class)->group(function(){
        Route::get('/admin/account', 'goToAccounts');
        Route::patch('/set-user-role', 'setUserRole');
        Route::patch('/change-user-status', 'changeUserStatus');
    });
    //Route for Admin Controller 
    Route::controller(AdminManager::class)->group(function(){
        Route::post('/add-admin', 'addAdmin');
        Route::patch('/update-admin','updateAdmin');
        Route::delete('/delete-admin', 'deleteAdmin');
    });
    //Route for Report Controller 
    Route::controller(ReportManager::class)->group(function(){
        Route::post('/generate-report', 'generateReport');
    });
    //Route for Transaction Report Controller 
    Route::controller(ReportTransactionManager::class)->group(function(){
        Route::post('/generate-transaction-report', 'generateTransactionReport');
    });
    //Route for Pdf Report Controller 
    Route::controller(PdfReportManager::class)->group(function(){
        Route::post('/add-report', 'addReport');
        Route::get('/admin/reports/monthly-report','goToMonthlyReports');
        Route::get('/admin/reports/quarterly-report','goToQuarterlyReports');
    });
    //Route for Trail Controller 
    Route::controller(TrailManager::class)->group(function(){
        Route::get('/admin/trails/user', 'goToTrails');
        Route::get('/admin/trails/admin', 'goToTrailsAdmin');
    });
    //Route for Dashboard Controller 
    Route::controller(DashboardAccountManager::class)->group(function(){
        Route::patch('/dashboard-change-user-role', 'setUserRoleDashboard');
        Route::patch('/dashboard-change-user-status', 'changeUserStatus');
        Route::patch('/dashboard-change-transaction-status', 'updateTransactionStatus');
    });
    //Route for Supplier Controller 
    Route::controller(SupplierManager::class)->group(function(){
        Route::post('/add-supplier', 'addSupplier');
        Route::patch('/update-supplier','updateSupplier');
        Route::delete('/delete-supplier','deleteSupplier');
    });
    //Route for Admin Function Controller 
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
    //Route for Transaction Controller 
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
    Route::controller(UserReportManager::class)->group(function() {
        Route::post('/generate-user-ledger-report', 'generateUserReportPdf');
    });
    //Route for User Function Controller 
    Route::controller(User_functionController::class)->group(function(){
        Route::get('/logoutUser', 'logoutUser');
    });
});
