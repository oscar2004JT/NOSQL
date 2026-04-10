<?php

use App\Http\Controllers\MercadoApiController;
use Illuminate\Support\Facades\Route;

Route::get('/usuarios/{userId}', [MercadoApiController::class, 'user']);
Route::get('/usuarios/{userId}/pedidos', [MercadoApiController::class, 'orders']);
Route::get('/usuarios/{userId}/pedidos/{orderId}', [MercadoApiController::class, 'orderDetail']);
