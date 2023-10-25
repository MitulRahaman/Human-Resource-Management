<?php

namespace App\Http\Controllers\Role;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\RoleService;
use Illuminate\Support\Facades\View;
use App\Http\Requests\RoleAddRequest;
use App\Http\Requests\RoleEditRequest;




class RoleController extends Controller
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
        View::share('main_menu', 'Roles');
        View::share('sub_menu', 'Roles');
    }
    public function index()
    {
        return \view('backend.pages.role.index');
    }
    public function fetchData()
    {

        return $this->roleService->fetchData();
    }
    public function create()
    {
        $permissions=$this->roleService->getAllPermissions();
        return \view('backend.pages.role.create', compact('permissions'));
    }
    public function store(RoleAddRequest $request)
    {
        try{
            $role = $this->roleService->createRole($request->validated(),(int)$request->id);

            return redirect('role/')->with('success', $role->getData()->message);

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Role could not be stored.");

        }

    }

    public function validate_inputs(Request $request)
    {
        return $this->roleService->validateInputs($request->all());

    }
    public function edit(int $id )
    {
        $role_info = $this->roleService->getRole($id);
        $permissions = $this->roleService->getAllPermissions();
        $permission_id = $this->roleService->getPermission($id);
        return \view('backend.pages.role.edit',compact('role_info','permissions','permission_id'));
    }
    public function update(RoleEditRequest $request)
    {
        try{
            $role = $this->roleService->edit($request->validated(),(int)$request->id);
            return redirect('role/')->with('success', $role->getData()->message);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Role could not be updated.");

        }
    }
    public function changeStatus(Request $request)
    {
        try{
            $id = $request->role_id;
            $role = $this->roleService->changeStatus($id);
            return redirect('role/')->with('success', $role->getData()->message);
        } catch (\Exception $exception) {
                return redirect()->back()->with('error', "OOPS! Role status could not be saved.");

            }

    }
    public function delete(Request $request)
    {
        try{
            $id = (int)$request->delete_role_id;
            $role= $this->roleService->delete($id);
            return redirect('role/')->with('success', $role->getData()->message);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Role could not be deleted.");

        }

    }
    public function restore(Request $request)
    {
        try{
            $id = (int)$request->restore_role_id;
            $role= $this->roleService->restore($id);
            return redirect('role/')->with('success', $role->getData()->message);


        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Role could not be restored.");


        }

    }
    public function validate_name(Request $request, int $id)
    {
        return $this->roleService->validateName($request->all(),$id);

    }
}
