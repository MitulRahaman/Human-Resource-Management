<?php

namespace App\Http\Controllers\Permission;

use App\Exports\ExportPermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionAddRequest;
use App\Http\Requests\PermissionEditRequest;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Excel;

//use App\Exports\ExportPermissions;


class PermissionController extends Controller
{
    private $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
        View::share('main_menu', 'Permissions');
        View::share('sub_menu', 'Permissions');
    }
    public function index()
    {
        return \view('backend.pages.permission.index');
    }
    public function create()
    {
        return \view('backend.pages.permission.create');
    }
    public function fetchData()
    {
        return $this->permissionService->fetchData();
    }
    public function store(PermissionAddRequest $request)
    {
        try{
            $response = $this->permissionService->createPermission($request->validated());
            if (is_int($response)) {
                return redirect('permission/')->with('success', 'Permission saved successfully.');
            } else {
                return redirect()->back()->with('error', $response);
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function changeStatus($id)
    {
        try{
            $id = (int) $id;
            $this->permissionService->changeStatus($id);
            return redirect('permission/')->with('success', "Permission status changed successfully.");
        } catch (\Exception $exception) {
                return redirect()->back()->with('error', "OOPS! Permission status could not be changed.");
            }
    }
    public function delete($id)
    {
        try{
            $id = (int) $id;
            $this->permissionService->delete($id);
            return redirect('permission/')->with('success', "Permission deleted successfully.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Permission could not be deleted.");
        }
    }
    public function edit($id )
    {
        $permission_info = $this->permissionService->getPermission($id);
        return \view('backend.pages.permission.edit',compact('permission_info'));
    }
    public function update(PermissionEditRequest $request)
    {
        try{
            $this->permissionService->edit($request->validated(),(int)$request->id);
            return redirect('permission/')->with('success', "Permission updated successfully.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Permission could not be updated.");
        }
    }
    public function restore($id)
    {
        try{
            $id = (int) $id;
            $this->permissionService->restore($id);
            return redirect('permission/')->with('success', "Permission restored successfully.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Permission could not be restored.");
        }
    }

    public function validate_inputs(Request $request)
    {
        return $this->permissionService->validateInputs($request->all());
    }
    public function validate_name(Request $request, int $id)
    {
        return $this->permissionService->validateName($request->all(),$id);
    }
    public function exportPermissionsData(){
        $permissionData = 'permissions.xlsx';
        return Excel::download(new ExportPermissions, $permissionData);
    }
}
