<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1') -> group(function(){
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/reset', [AuthController::class, 'resetPassword']);
    Route::post('/changePassword', [AuthController::class, 'changePassword']);
    Route::post('/users/{userId}/income', [AuthController::class, 'updateUserIncome']);
    Route::post('/users/{userId}/expense', [AuthController::class, 'updateUserExpense']);
    Route::post('/users/{userId}/categories', [CategoryController::class, 'addCategory']);
    Route::get('/users/{userId}/categories', [CategoryController::class, 'getAllCategory']);
    Route::get('/users/{userId}/categories/{categoryId}', [CategoryController::class, 'getCategoryById']);
    Route::post('/categories/{categoryId}', [CategoryController::class, 'updateCategory']);
    Route::post('/transactions', [TransactionController::class, 'addTransaction']);
    Route::post('/transactions/{transactionId}', [TransactionController::class, 'updateTransaction']);
    Route::delete('/transactions/{transactionId}', [TransactionController::class, 'deleteTransaction']);
    Route::get('/transactions/{transactionId}', [TransactionController::class, 'getTransactionById']);
    Route::get('/transactions/recent/{userId}', [TransactionController::class, 'getRecentTransaction']);
    Route::get('/transactions/{month}/{userId}', [TransactionController::class, 'getTransactionByMonth']);
    Route::get('/transactions/{month}/incometotal/{userId}', [TransactionController::class, 'getIncomeCategoryTotal']);
    Route::get('/transactions/{month}/expensetotal/{userId}', [TransactionController::class, 'getExpenseCategoryTotal']);
});
