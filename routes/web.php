<?php

use App\Http\Controllers\Acl\RoleController;
use App\Http\Controllers\Acl\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Back\ImportEwebController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Master\ProductController;
use App\Http\Controllers\Master\QrcodeController;
use App\Http\Controllers\Transaction\CustomerController as TransactionCustomerController;
use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

Route::get('backoffice', function () {
    return redirect(route('dashboard'));
});

// Override halaman login
Route::get('admin-orivetama', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('admin-orivetama', [LoginController::class, 'login']);

// Logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// backoffice
Route::get('backoffice/dashboard', [HomeController::class, 'index'])->name('dashboard');

Route::prefix('backoffice')->middleware(['auth'])->group(function () {

    // Change Password
    Route::get('change-password', [UserController::class, 'changePassword'])->name('users.change-password');
    Route::patch('change-password', [UserController::class, 'changePasswordStore'])->name('users.change-password-update');

    // User management
    Route::name('user-management.')->group(function () {
        Route::resource('user-management/roles', RoleController::class);
        Route::resource('user-management/users', UserController::class);
    });

    Route::get('import-pos-eweb', [ImportEwebController::class, 'index'])->name('import-eweb');
});
