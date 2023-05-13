<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::group(['middleware'=>['auth']],function()
{
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/products',[MainController::class,'productlist'])->name('products');   
    Route::get('/add-product',[MainController::class,'addProduct'])->name('addProduct'); 
    Route::post('/add-product',[MainController::class,'storeProduct'])->name('storeProduct');
    Route::get('/edit-product/{id}',[MainController::class,'editProduct'])->name('editProduct');
    Route::post('/update-product',[MainController::class,'updateProduct'])->name('updateProduct');
    Route::get('/delete-product/{id}',[MainController::class,'deleteProduct'])->name('deleteProduct');
    Route::get('/orders',[MainController::class,'orders'])->name('orders');
    Route::get('/add-order',[MainController::class,'addOrder'])->name('addOrder');
    Route::post('/store-order',[MainController::class,'storeOrder'])->name('storeOrder');
    Route::get('/edit-order/{id}',[MainController::class,'editOrder'])->name('editOrder');
    Route::post('/update-order',[MainController::class,'updateOrder'])->name('updateOrder');
    Route::get('/delete-order/{id}',[MainController::class,'deleteOrder'])->name('deleteOrder');
    Route::get('/invoice/{id}',[MainController::class,'invoice'])->name('invoice');
      
});


