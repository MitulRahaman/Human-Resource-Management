<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Branch\BranchController;
use App\Http\Controllers\Permission\PermissionController;

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

Route::get('login', [AuthController::class, 'index'])->name('viewLogin');
Route::post('login', [AuthController::class, 'authenticate'])->name('login');


Route::group(['middleware'=> 'auth'], function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::prefix('branch')->group(function() {
        Route::get('/', [BranchController::class, 'index']);
        Route::get('/add', [BranchController::class, 'create']);
        Route::post('/store', [BranchController::class, 'store']);
        Route::patch('/{id}/update', [BranchController::class, 'update'])->name('branch.update');
        Route::delete('/{id}/delete', [BranchController::class, 'destroy'])->name('branch.destroy');
        Route::post('{id}/restore', [BranchController::class, 'restore'])->name('branch.restore');
        Route::get('{id}/edit', [BranchController::class, 'edit'])->name('branch.edit');
        Route::post('verifydata', [BranchController::class, 'verifydata'])->name('verifydata');
    });

    Route::prefix('permission')->group(function() {
        Route::get('/', [PermissionController::class, 'index']);
        Route::get('/add', [PermissionController::class, 'create']);
        Route::post('/store', [PermissionController::class, 'store']);
        Route::get('/get_permission_data', [PermissionController::class, 'fetchData']);
        Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('edit_permission');
    });
});
