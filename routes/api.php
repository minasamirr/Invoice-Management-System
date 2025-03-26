<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\InvoiceController;
use App\Http\Controllers\API\InvoiceLogController;
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

// public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Employees can only update invoices
    Route::middleware('can:update,invoice')->group(function () {
        Route::put('/invoices/{invoice}', [InvoiceController::class, 'update']);
    });

    // Admins can create and delete invoices
    Route::middleware('can:manageInvoices')->group(function () {
        Route::post('/invoices',[InvoiceController::class, 'store']);
        Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy']);
        Route::get('/invoice_logs', [InvoiceLogController::class, 'index']);
    });

    Route::get('/invoices/search', [InvoiceController::class, 'search']);
    Route::get('/invoices', [InvoiceController::class, 'index']);
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show']);
});
