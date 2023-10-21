<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Api\Auth\LoginController;
use App\Http\Controllers\V1\Api\Admin\CategoryController;
use App\Http\Controllers\V1\Api\Admin\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login',[LoginController::class,'login']);
Route::prefix('admin')->middleware('auth:sanctum')->group(function(){
    Route::post('storeSubCategory',[CategoryController::class,'storeSubCategory']);
    Route::apiResource('category',CategoryController::class);
    Route::get('transactionUpdateAllStatus',[TransactionController::class,'updateAllTransactionStatus']);
    Route::get('viewCustomerTransaction',[TransactionController::class,'viewCustomerTransaction']);
    Route::post('storeTransactionDetail',[TransactionController::class,'storeTransactionDetail']);
    Route::get('reportMonthly',[TransactionController::class,'reportMonthly']);
    Route::apiResource('transaction',TransactionController::class);
});
