<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});


/* -- ADMIN --*/
Route::get('/admin/dashboard', function () {
    return view('admin/dashboard');
});
Route::get('/admin/items', function () {
    return view('admin/items');
});
Route::get('/admin/transaction', function () {
    return view('admin/transaction');
});
Route::get('/admin/request', function () {
    return view('admin/request');
});
Route::get('/admin/report', function () {
    return view('admin/report');
});
Route::get('/admin/analytic', function () {
    return view('admin/analytic');
});
Route::get('/admin/account', function () {
    return view('admin/account');
});
Route::get('/admin/audit', function () {
    return view('admin/audit');
});
Route::get('/admin/profile', function () {
    return view('admin/profile');
});



/* -- USER -- */
Route::get('/user/dashboard', function () {
    return view('user/dashboard');
});
Route::get('/user/request', function () {
    return view('user/request');
});
Route::get('/user/history', function () {
    return view('user/history');
});
Route::get('/user/profile', function () {
    return view('user/profile');
});



/* -- ADMIN HEAD -- */
Route::get('/admin_head/dashboard', function () {
    return view('admin_head/dashboard');
});
Route::get('/admin_head/transaction', function () {
    return view('admin_head/transaction');
});
Route::get('/admin_head/request', function () {
    return view('admin_head/request');
});



/* -- ADMIN HEAD -- */
Route::get('/admin_checker/dashboard', function () {
    return view('admin_checker/dashboard');
});
Route::get('/admin_checker/transaction', function () {
    return view('admin_checker/transaction');
});
Route::get('/admin_checker/request', function () {
    return view('admin_checker/request');
});