<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Client\ClientProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//AUTH
Route::post('/register',[AuthController::class,'register'])->name('auth.register');
Route::post('/login',[AuthController::class,'login'])->name('auth.login');
Route::post('/login-google',[AuthController::class,'loginGoogle'])->name('auth.login');

//private
Route::group(['middleware' => ['auth:sanctum']], function(){

    Route::put('/profile',[AuthController::class,'update'])->name('auth.profile');
    Route::post('/logout',[AuthController::class,'logout'])->name('auth.logout');

    //PEDIDOS
    Route::get('/orders/{id}',[OrderController::class,'index'])->name('orders.index');//clientes
    Route::get('/orders/show/{id}',[OrderController::class,'prod'])->name('orders.show');
    Route::get('/admin/orders',[OrderController::class,'admin'])->name('admin.orders');//admin
    Route::post('/update-order/{id}', [OrderController::class, 'updateOrder'])->name('update.order');//estatus actualizar

    Route::post('/order_register',[OrderController::class,'createOrder'])->name('create.order');
    Route::post('/create_detail_order/{IdOrder}',[OrderController::class,'createDetailOrder'])->name('create.detail.order');

    
    //PRODUCTS
    Route::get('/products',[ProductController::class, 'index'])->name('products.index');
   // Route::get('/products/create',[ProductController::class, 'create'])->name('products.create');
    Route::post('/products/store',[ProductController::class, 'store'])->name('products.store'); 
   // Route::get('/products/edit/{id}',[ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/update/{id}',[ProductController::class, 'update'])->name('products.update');
    Route::get('/products/show/{id}',[ProductController::class, 'show'])->name('products.show');
    Route::delete('/products/delete/{id}',[ProductController::class, 'delete'])->name('products.delete');

    //SUPPLIERS
    Route::get('/suppliers',[SupplierController::class,'index'])->name('suppliers.index');
   // Route::get('/suppliers/create',[SupplierController::class,'create'])->name('suppliers.create');
    Route::post('/suppliers/store',[SupplierController::class, 'store'])->name('suppliers.store');
   // Route::get('/suppliers/edit/{id}',[SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/update/{id}',[SupplierController::class, 'update'])->name('suppliers.update');
    Route::get('/suppliers/show/{id}',[SupplierController::class, 'show'])->name('suppliers.show');
    Route::delete('/suppliers/delete/{id}',[SupplierController::class, 'delete'])->name('suppliers.delete');

});


//CLIENTS
Route::get('/clients',[ClientController::class, 'index'])->name('clients.index');
Route::get('/clients/create',[ClientController::class, 'create'])->name('clients.create');

Route::post('/clients/store',[ClientController::class, 'store'])->name('clients.store');

Route::get('/clients/edit/{id}',[ClientController::class, 'edit'])->name('clients.edit');
Route::put('/clients/update/{id}',[ClientController::class, 'update'])->name('clients.update');

Route::get('/clients/show/{id}',[ClientController::class, 'show'])->name('clients.show');
Route::delete('/clients/delete/{id}',[ClientController::class, 'delete'])->name('clients.delete');



//users
// Route::get('/users',[ClientProductController::class,'view'])->name('users.index');
// // Route::get('/users/create',[ClientProductController::class,'create'])->name('users.create');

// Route::post('/users/store',[ClientProductController::class, 'store'])->name('users.store');

// Route::get('/users/edit/{id}',[ClientProductController::class, 'edit'])->name('users.edit');
Route::put('/users/update/{id}',[ClientProductController::class, 'update'])->name('users.update');

// //users
// Route::view('/users','users/index')->name('users.index');
// Route::view('/users/create','/users/create')->name('users.create');

Route::get('/users/show/{id}',[ClientProductController::class, 'show'])->name('users.show');
Route::delete('/users/delete/{id}',[ClientProductController::class, 'delete'])->name('users.delete');




Route::get('users', [ClientProductController::class, 'view'])->name('client.index');

Route::get('/admin/user/{userId}',[ClientProductController::class, 'showOrders'])->name('user.orders');// pedidos
Route::get('/users/edit/{userId}',[ClientProductController::class, 'edit'])->name('user.edit');// editar weones
Route::put('/users/{userId}',[ClientProductController::class, 'update'])->name('user.update');// editar weones

//PEDIDOS
//Route::get('/orders',[OrderController::class,'index'])->name('orders.index');//clientes



