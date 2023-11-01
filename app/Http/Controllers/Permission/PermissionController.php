<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionAddRequest;
use App\Http\Requests\PermissionEditRequest;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

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
            if($this->permissionService->changeStatus($id))
                return redirect('permission/')->with('success', "Permission status changed successfully.");
            return redirect('permission/')->with('error', "Permission status not changed.");
        } catch (\Exception $exception) {
                return redirect()->back()->with('error', "OOPS! Permission status could not be changed.");
            }
    }
    public function delete($id)
    {
        try{
            if($this->permissionService->delete($id))
                return redirect('permission/')->with('success', "Permission deleted successfully.");
            return redirect('permission/')->with('error', "Permission not deleted.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Permission could not be deleted.");
        }
    }
    public function edit($id )
    {
        $permission_info = $this->permissionService->getPermission($id);
        if($permission_info=="Restore first")
            return redirect()->back()->with('error', $permission_info);
        return \view('backend.pages.permission.edit',compact('permission_info'));
    }
    public function update(PermissionEditRequest $request)
    {
        try{
            if($this->permissionService->edit($request->validated()))
                return redirect('permission/')->with('success', "Permission updated successfully.");
            return redirect('permission/')->with('success', "Permission not updated.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Permission could not be updated.");
        }
    }
    public function restore($id)
    {
        try{
            if($this->permissionService->restore($id))
                return redirect('permission/')->with('success', "Permission restored successfully.");
            return redirect('permission/')->with('success', "Permission not restored.");
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
}
