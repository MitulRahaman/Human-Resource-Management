<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Branch\BranchController;
use App\Http\Controllers\Department\DepartmentController;
use App\Http\Controllers\Leave\LeaveController;


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
    Route::post('leave_types/get_data', [LeaveController::class, 'getTypeWiseTotalLeavesData']);

    Route::prefix('branch')->group(function() {
        Route::get('/', [BranchController::class, 'index']);
        Route::get('/add', [BranchController::class, 'create']);
        Route::post('/store', [BranchController::class, 'store']);
        Route::get('{id}/edit', [BranchController::class, 'edit'])->name('branch.edit');
        Route::patch('/{id}/update', [BranchController::class, 'update'])->name('branch.update');
        Route::delete('/{id}/delete', [BranchController::class, 'destroy'])->name('branch.destroy');
        Route::post('{id}/restore', [BranchController::class, 'restore'])->name('branch.restore');
        Route::get('{id}/status', [BranchController::class, 'status'])->name('branch.status');
        Route::post('verifydata', [BranchController::class, 'verifydata'])->name('verifydata');
        Route::patch('/updatedata', [BranchController::class, 'updatedata'])->name('updatedata');
    });

    Route::prefix('department')->group(function() {
        Route::get('/', [DepartmentController::class, 'index']);
        Route::get('/add', [DepartmentController::class, 'create']);
        Route::post('/store', [DepartmentController::class, 'store']);
        Route::get('{id}/edit', [DepartmentController::class, 'edit'])->name('department.edit');
        Route::patch('/{id}/update', [DepartmentController::class, 'update'])->name('department.update');
        Route::delete('/{id}/delete', [DepartmentController::class, 'destroy'])->name('department.destroy');
        Route::post('{id}/restore', [DepartmentController::class, 'restore'])->name('department.restore');
        Route::get('{id}/status', [DepartmentController::class, 'status'])->name('department.status');
        Route::post('/verifydept', [DepartmentController::class, 'verifydept'])->name('verifydept');
        Route::patch('/updatedept', [DepartmentController::class, 'updatedept'])->name('updatedept');
    });

    Route::prefix('leave')->group(function() {
        Route::get('/', [LeaveController::class, 'index']);
        Route::get('/add', [LeaveController::class, 'create']);
        Route::get('/manage', [LeaveController::class, 'manage']);
        Route::post('/store', [LeaveController::class, 'store']);
        Route::get('{id}/edit', [LeaveController::class, 'edit'])->name('leave.edit');
        Route::patch('/{id}/update', [LeaveController::class, 'update'])->name('leave.update');
        Route::delete('/{id}/delete', [LeaveController::class, 'destroy'])->name('leave.destroy');
        Route::post('{id}/restore', [LeaveController::class, 'restore'])->name('leave.restore');
        Route::get('{id}/status', [LeaveController::class, 'status'])->name('leave.status');
        Route::post('verifyleave', [LeaveController::class, 'verifyleave'])->name('verifyleave');
        Route::patch('/updateleave', [LeaveController::class, 'updateleave'])->name('updateleave');
        Route::post('/addTotalLeave/{id}', [LeaveController::class, 'addTotalLeave']);
    });
    

});
