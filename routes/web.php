<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Bank\BankController;
use App\Http\Controllers\Degree\DegreeController;
use App\Http\Controllers\Institute\InstituteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Branch\BranchController;
use App\Http\Controllers\Department\DepartmentController;
use App\Http\Controllers\Leave\LeaveController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\LeaveApply\LeaveApplyController;
use App\Http\Controllers\LeaveApply\LeaveApplicationMailController;

use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Menu\MenuController;
use App\Http\Controllers\Designation\DesignationController;
use App\Http\Controllers\Calender\CalenderController;
use App\Http\Controllers\Asset\AssetController;
use App\Http\Controllers\Requisition\RequisitionController;
use App\Http\Controllers\Log\LogController;


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
Route::group(['middleware'=> 'log'], function() {
Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [AuthController::class, 'index'])->name('viewLogin');
Route::post('login', [AuthController::class, 'authenticate'])->name('login');

Route::group(['middleware'=> 'auth'], function() {
    Route::get('change_password', [AuthController::class, 'viewChangePassword']);
    Route::post('change_password', [AuthController::class, 'changePassword']);
    Route::post('change_password/validate_inputs', [AuthController::class, 'validatePasswords']);

    Route::prefix('dashboard')->group(function() {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/get_requisition_data', [DashboardController::class, 'fetchRequisitionData']);
        Route::get('/get_on_leave_data', [DashboardController::class, 'fetchOnLeaveData']);
        Route::get('/get_pending_leave_data', [DashboardController::class, 'fetchPendingLeaveData']);
    });
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
    Route::prefix('user')->group(function() {
        Route::post('get_table_data', [UserController::class, 'getTableData']);
        Route::get('create', [UserController::class, 'create']);
        Route::get('manage', [UserController::class, 'manage']);
        Route::post('store', [UserController::class, 'store']);
        Route::get('/{id}/edit', [UserController::class, 'editBasicInfo']);
        Route::patch('/update/{id}', [UserController::class, 'update']);
        Route::post('/{id}/delete', [UserController::class, 'destroy']);
        Route::post('/{id}/restore', [UserController::class, 'restore']);
        Route::post('/{id}/change_status', [UserController::class, 'changeStatus']);
        Route::post('getDeptDesg', [UserController::class, 'getDeptDesg']);
        Route::post('verifyUser', [UserController::class, 'verifyUser']);
        Route::patch('updateUser', [UserController::class, 'updateUser']);

        Route::get('profile/{id?}', [UserController::class, 'show'])->name('profile');
        Route::get('profile/{id?}/edit', [UserController::class, 'editData']);
        Route::post('profile/{id?}/update', [UserController::class, 'updateData']);
        Route::delete('profile/{id}/delete_academic_info', [UserController::class, 'deleteAcademicInfo']);

        Route::get('/{id}/distribute_asset', [UserController::class, 'distributeAsset']);
        Route::post('/{id}/update_distribute_asset', [UserController::class, 'updateDistributeAsset']);
    });
    Route::prefix('leaveApply')->group(function() {
        Route::post('get_table_data', [LeaveApplyController::class, 'getTableData']);
        Route::get('apply', [LeaveApplyController::class, 'apply']);
        Route::get('manage', [LeaveApplyController::class, 'manage']);
        Route::post('store', [LeaveApplyController::class, 'store']);
        Route::get('/{id}/edit', [LeaveApplyController::class, 'edit']);
        Route::patch('/update/{id}', [LeaveApplyController::class, 'update']);
        Route::post('status/{id}/approve', [LeaveApplyController::class, 'approveLeave']);
        Route::post('status/{id}/reject', [LeaveApplyController::class, 'rejectLeave']);
        Route::post('status/{id}/recommend', [LeaveApplyController::class, 'recommendLeave']);
        Route::get('status/{id}/cancel', [LeaveApplyController::class, 'cancelLeave']);
        Route::get('/{id}/delete', [LeaveApplyController::class, 'delete']);
        Route::get('leaveReports', [LeaveApplyController::class, 'leaveReports']);
        Route::post('get_report_data', [LeaveApplyController::class, 'getReportData']);
    });
    Route::prefix('bank')->group(function() {
        Route::get('/', [BankController::class, 'index']);
        Route::get('/get_bank_data', [BankController::class, 'fetchData']);
        Route::get('/add', [BankController::class, 'create']);
        Route::post('/validate_inputs', [BankController::class, 'validate_inputs']);
        Route::post('/store', [BankController::class, 'store']);
        Route::get('/{bank}/edit', [BankController::class, 'edit'])->name('edit_bank');
        Route::post('/{id}/update', [BankController::class, 'update']);
        Route::post('/{id}/validate_name',[BankController::class, 'validate_name']);
        Route::post('/{id}/delete', [BankController::class, 'delete']);
        Route::post('/{id}/restore', [BankController::class, 'restore']);
    });
    Route::prefix('designation')->group(function() {
        Route::get('/', [DesignationController::class, 'index']);
        Route::get('/get_designation_data', [DesignationController::class, 'fetchData']);
        Route::get('/add', [DesignationController::class, 'create']);
        Route::post('/fetch_departments', [DesignationController::class, 'fetchDepartments']);
        Route::post('/store', [DesignationController::class, 'store']);
        Route::post('/validate_designation_inputs', [DesignationController::class, 'validate_inputs']);
        Route::post('/{id}/change_status', [DesignationController::class, 'changeStatus']);
        Route::post('/{id}/delete', [DesignationController::class, 'delete']);
        Route::post('/{id}/restore', [DesignationController::class, 'restore']);
        Route::get('/{designation}/edit', [DesignationController::class, 'edit'])->name('edit_designation');
        Route::post('/{id}/validate_designation_name',[DesignationController::class, 'validate_name']);
        Route::post('/{id}/update', [DesignationController::class, 'update']);
    });
    Route::prefix('calender')->group(function() {
        Route::get('/', [CalenderController::class, 'index']);
        Route::get('/manage', [CalenderController::class, 'manage']);
        Route::post('/get_dates',  [CalenderController::class, 'getDates']);
        Route::post('/store', [CalenderController::class, 'store']);
        Route::get('/get_events', [CalenderController::class, 'getEvents']);
        Route::get('/save_event', [CalenderController::class, 'saveEvent']);
        Route::get('/upload', [CalenderController::class, 'upload']);
        Route::post('/save_excel', [CalenderController::class, 'saveExcel']);
        Route::post('/update_event', [CalenderController::class, 'updateEvent']);
        Route::post('/add_event', [CalenderController::class, 'addEvent']);
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
    Route::prefix('institute')->group(function() {
        Route::get('/', [InstituteController::class, 'index']);
        Route::get('/get_institute_data', [InstituteController::class, 'fetchData']);
        Route::get('/add', [InstituteController::class, 'create']);
        Route::post('/validate_inputs', [InstituteController::class, 'validate_inputs']);
        Route::post('/store', [InstituteController::class, 'store']);
        Route::get('/{institute}/edit', [InstituteController::class, 'edit'])->name('edit_institute');
        Route::post('/{id}/update', [InstituteController::class, 'update']);
        Route::post('/{id}/validate_name',[InstituteController::class, 'validate_name']);
        Route::post('/{id}/delete', [InstituteController::class, 'delete']);
        Route::post('/{id}/restore', [InstituteController::class, 'restore']);
        Route::post('/{id}/change_status', [InstituteController::class, 'changeStatus']);
    });
    Route::prefix('degree')->group(function() {
        Route::get('/', [DegreeController::class, 'index']);
        Route::get('/get_degree_data', [DegreeController::class, 'fetchData']);
        Route::get('/add', [DegreeController::class, 'create']);
        Route::post('/validate_inputs', [DegreeController::class, 'validate_inputs']);
        Route::post('/store', [DegreeController::class, 'store']);
        Route::get('/{degree}/edit', [DegreeController::class, 'edit'])->name('edit_degree');
        Route::post('/{id}/update', [DegreeController::class, 'update']);
        Route::post('/{id}/validate_name',[DegreeController::class, 'validate_name']);
        Route::post('/{id}/delete', [DegreeController::class, 'delete']);
        Route::post('/{id}/restore', [DegreeController::class, 'restore']);
    });
    Route::prefix('requisition')->group(function() {
        Route::get('/', [RequisitionController::class, 'index']);
        Route::get('/get_requisition_data', [RequisitionController::class, 'fetchData']);
        Route::get('/request', [RequisitionController::class, 'create']);
        Route::post('/store', [RequisitionController::class, 'store']);
        Route::get('/{id}/edit', [RequisitionController::class, 'edit']);
        Route::post('/{id}/update', [RequisitionController::class, 'update']);
        Route::get('/{id}/delete', [RequisitionController::class, 'delete']);
        Route::post('status/{id}/approve', [RequisitionController::class, 'approve']);
        Route::post('status/{id}/reject', [RequisitionController::class, 'reject']);
        Route::get('status/{id}/cancel', [RequisitionController::class, 'cancel']);
    });

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


        Route::prefix('assetsType')->group(function() {
            Route::get('/', [AssetController::class, 'assetTypeIndex']);
            Route::get('/get_asset_type_data', [AssetController::class, 'fetchDataAssetType']);
            Route::get('/add', [AssetController::class, 'createAssetType']);
            Route::post('/validate_inputs', [AssetController::class, 'validate_inputs_asset_type']);
            Route::post('/store', [AssetController::class, 'storeAssetType']);
            Route::get('/{id}/edit_asset_type', [AssetController::class, 'edit_asset_type'])->name('edit_asset_type');
            Route::post('/{id}/update_asset_type', [AssetController::class, 'update_asset_type']);
            Route::post('/{id}/validate_name',[AssetController::class, 'validate_name_asset_type']);
            Route::post('/{id}/delete', [AssetController::class, 'deleteAssetType']);
            Route::post('/{id}/restore', [AssetController::class, 'restoreAssetType']);
            Route::post('/{id}/change_status', [AssetController::class, 'changeStatusAssetType']);
        });

        Route::prefix('asset')->group(function() {
            Route::get('/', [AssetController::class, 'index']);
            Route::get('/get_asset_data', [AssetController::class, 'fetchData']);
            Route::get('/add', [AssetController::class, 'create']);
            Route::post('/store', [AssetController::class, 'store']);
            Route::get('/{id}/edit', [AssetController::class, 'edit'])->name('edit_asset');
            Route::post('/{id}/update', [AssetController::class, 'update']);
            Route::post('/{id}/delete', [AssetController::class, 'delete']);
            Route::post('/{id}/restore', [AssetController::class, 'restore']);
            Route::post('/{id}/change_status', [AssetController::class, 'changeStatus']);
        });
        Route::prefix('log')->group(function() {
            Route::get('/', [LogController::class, 'index']);
            Route::get('/get_log_data', [LogController::class, 'fetchData']);
        });

    });

});

