<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceLogController;
use Illuminate\Support\Facades\Route;

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

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('auth.register');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Protect routes with authentication middleware as needed
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('invoices.index');
    });

    Route::get('/invoices/search', [InvoiceController::class, 'search'])->name('invoices.search');
    Route::resource('invoices', InvoiceController::class);
    Route::resource('invoice_logs', InvoiceLogController::class);
});
