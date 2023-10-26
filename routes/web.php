<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Branch\BranchController;
use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Menu\MenuController;

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
        Route::get('/store', [BranchController::class, 'store']);
    });
    Route::group(['middleware'=> 'superUser'], function() {
    Route::prefix('permission')->group(function() {

        Route::get('/', [PermissionController::class, 'index']);
        Route::get('/get_permission_data', [PermissionController::class, 'fetchData']);

        Route::get('/add', [PermissionController::class, 'create']);
        Route::post('/store', [PermissionController::class, 'store']);

        Route::post('/{id}/change_status', [PermissionController::class, 'changeStatus']);

        Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('edit_permission');
        Route::post('/{id}/update', [PermissionController::class, 'update']);

        Route::post('/validate_inputs', [PermissionController::class, 'validate_inputs']);
        Route::post('/{id}/validate_name',[PermissionController::class, 'validate_name']);
        Route::post('/check_edit', [PermissionController::class, 'checkEdit']);

        Route::post('/{id}/delete', [PermissionController::class, 'delete']);
        Route::post('/{id}/restore', [PermissionController::class, 'restore']);
       
        Route::get('export-permissions-data', [PermissionController::class, 'exportPermissionsData']);

    });
    Route::prefix('role')->group(function() {

            Route::get('/', [RoleController::class, 'index']);
            Route::get('/get_role_data', [RoleController::class, 'fetchData']);

            Route::get('/add', [RoleController::class, 'create']);
            Route::post('/store', [RoleController::class, 'store']);

            Route::post('/validate_role_inputs', [RoleController::class, 'validate_inputs']);
            Route::post('/{id}/validate_role_name',[RoleController::class, 'validate_name']);

            Route::post('/{id}/change_status', [RoleController::class, 'changeStatus']);

            Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit_role');
            Route::post('/{id}/update', [RoleController::class, 'update']);

            Route::post('/{id}/delete', [RoleController::class, 'delete']);
            Route::post('/{id}/restore', [RoleController::class, 'restore']);

        });
        Route::prefix('menu')->group(function() {

            Route::get('/', [MenuController::class, 'index']);
            Route::get('/get_menu_data', [MenuController::class, 'fetchData']);

            Route::get('/add', [MenuController::class, 'create']);
            Route::post('/store', [MenuController::class, 'store']);

            Route::post('/{id}/change_status', [MenuController::class, 'changeStatus']);

            Route::post('/{id}/delete', [MenuController::class, 'delete']);
            Route::post('/{id}/restore', [MenuController::class, 'restore']);

            Route::get('/{menu}/edit', [MenuController::class, 'edit'])->name('edit_menu');
            Route::post('/{id}/update', [MenuController::class, 'update']);
        });


    });


});
