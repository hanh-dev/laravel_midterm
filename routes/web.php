<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::resource('/products', ProductController::class);

Route::get('/home', [PageController::class, 'index']);
Route::get('/add_to_cart', [PageController::class, 'addCart'])->name('themgiohang');
Route::get('/type/{id}', [PageController::class, 'getLoaiSp']);
Route::get('/detail/{id}', [PageController::class, 'getDetail']);
Route::get('/aboutUs', [PageController::class, 'getAboutUs']);
Route::get('/contactUs', [PageController::class, 'getContact']);
Route::post('/comment/{id}', [PageController::class, 'comment']);

// Admin
Route::get('/admin', [PageController::class, 'getIndexAdmin']);
Route::get('/admin-add-form', [PageController::class, 'getAdminAdd'])->name('add-product');
Route::post('/admin-add-form', [PageController::class, 'postAdminAdd']);

Route::get('/admin-edit-form/{id}', [PageController::class, 'getAdminEdit']);
Route::post('/admin-edit/{id}', [PageController::class, 'postAdminEdit']);

Route::post('/admin-delete/{id}', [PageController::class, 'postAdminDelete']);
Route::get('/admin-export', [PageController::class, 'exportAdminProduct'])->name('export');
// Route::get('/return-vnpay', function() {
//     return view('');
// });

Route::get('/signup', [AuthController::class, 'showSignupForm']);
Route::post('/signup', [AuthController::class, 'register']);
Route::get('/signin', [AuthController::class, 'showSigninForm']);
Route::post('/signin', [AuthController::class, 'login']);



