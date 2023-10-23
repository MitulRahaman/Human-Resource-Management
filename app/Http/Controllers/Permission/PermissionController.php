<?php

namespace App\Http\Controllers\Permission;

use App\Exports\ExportPermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionAddRequest;
use App\Http\Requests\PermissionEditRequest;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
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
            $permission = $this->permissionService->createPermission($request->validated(),(int)$request->id);
           
                return redirect('permission/')->with('success', $permission->getData()->message);
            
        } catch (\Exception $exception) {
            // return redirect()->back()->with('error', $exception->getMessage());
            return redirect()->back()->with('error', "OOPS! Your Permission could not be stored.");

        }

    }
    public function changeStatus(Request $request)
    {
        try{
            $id = $request->permission_id;
            $permission = $this->permissionService->changeStatus($id);
            return redirect('permission/')->with('success', $permission->getData()->message);
        } catch (\Exception $exception) {
                // return redirect()->back()->with('error', $exception->getMessage());
                return redirect()->back()->with('error', "OOPS! Your Permission status could not be saved.");

            }

    }
    public function delete(Request $request)
    {
        try{
            $id = (int)$request->delete_permission_id;
            $permission= $this->permissionService->delete($id);
            return redirect('permission/')->with('success', $permission->getData()->message);
        } catch (\Exception $exception) {
            // return redirect()->back()->with('error', $exception->getMessage());
            return redirect()->back()->with('error', "OOPS! Your Permission could not be deleted.");

        }

    }
    public function edit(int $id )
    {
        $permission_info = $this->permissionService->getPermission($id);
        return \view('backend.pages.permission.edit',compact('permission_info'));
    }
    public function update(PermissionEditRequest $request)
    {
        try{
            $permission = $this->permissionService->edit($request->validated(),(int)$request->id);
            return redirect('permission/')->with('success', $permission->getData()->message);
        } catch (\Exception $exception) {
            // return redirect()->back()->with('error', $exception->getMessage());
            return redirect()->back()->with('error', "OOPS! Your Permission could not be updated.");

        }
    }
    public function restore(Request $request)
    {
        try{
            $id = (int)$request->restore_permission_id;
            $permission= $this->permissionService->restore($id);
            return redirect('permission/')->with('success', $permission->getData()->message);


        } catch (\Exception $exception) {
            // return redirect()->back()->with('error', $exception->getMessage());
            return redirect()->back()->with('error', "OOPS! Your Permission could not be restored.");


        }

    }

    public function validate_inputs(Request $request)
    {

        $response = $this->permissionService->validateInputs($request->all());
        return $response;

    }
    public function validate_name(Request $request)
    {

        $response = $this->permissionService->validateName($request->all());
        return $response;

    }
    public function exportPermissionsData(){
        $permissionData = 'permissions.xlsx';
        return Excel::download(new ExportPermissions, $permissionData);
    }

}
