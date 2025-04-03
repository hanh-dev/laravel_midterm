<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('index');
});

// Route::resource('/products', ProductController::class);

Route::get('/home', [PageController::class, 'index'])->name('trang-chu');
Route::get('/add_to_cart', [PageController::class, 'addCart'])->name('themgiohang');
Route::get('/type/{id}', [PageController::class, 'getLoaiSp'])->name('loaisanpham');
Route::get('/detail/{id}', [PageController::class, 'getDetail']);
Route::get('/aboutUs', [PageController::class, 'getAboutUs'])->name('aboutUs');
Route::get('/contactUs', [PageController::class, 'getContact'])->name('contactUs');
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

Route::get('/search', [PageController::class, 'find']);

// Cart
Route::get('add-to-cart/{id}', [PageController::class, 'getAddToCart'])->name('themgiohang');
Route::get('add-cart/{id}', [PageController::class, 'getDelItemCart'])->name('xoagiohang');
// Route::get('/products', [AuthController::class, 'products']);
// Route::get('/detail/{id}', [AuthController::class, 'detail']);
// Route::get('/delete/{id}', [AuthController::class, 'delete']);
// Route::put('/update/{id}', [AuthController::class, 'update']);
Route::middleware('web')->get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->json(['message' => 'CSRF token set']);
});

Route::get('/csrf-token', function () {
    return response()->json([
        'csrf_token' => csrf_token()
    ]);
});

Route::get('/api/v1/products', [AuthController::class, 'products']);
Route::get('/api/v1/products/{id}', [AuthController::class, 'detail']);
Route::post('/api/v1/products', [AuthController::class, 'store']);
Route::get('/api/v1/products/delete/{id}', [AuthController::class, 'delete']);
Route::put('/api/v1/products/update/{id}', [AuthController::class, 'updateProduct']);