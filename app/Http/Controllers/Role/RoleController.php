<?php

namespace App\Http\Controllers\Role;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\RoleService;
use Illuminate\Support\Facades\View;
use App\Http\Requests\RoleAddRequest;
use App\Http\Requests\RoleEditRequest;
use App\Traits\AuthorizationTrait;

class RoleController extends Controller
{
    use AuthorizationTrait;
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
        View::share('main_menu', 'Roles');
        View::share('sub_menu', 'Roles');
    }
    public function index()
    {
        $hasRoleManagePermission = $this->setId(auth()->user()->id)->setSlug('manageRole')->hasPermission();
        $addRolePermission = $this->setSlug('addRole')->hasPermission();
        return \view('backend.pages.role.index', compact('hasRoleManagePermission','addRolePermission'));
    }
    public function fetchData()
    {
        return $this->roleService->fetchData();
    }
    public function create()
    {
        abort_if(!$this->setSlug('addRole')->hasPermission(), 403, 'You don\'t have permission!');
        $permissions=$this->roleService->getPermissions();
        $branches=$this->roleService->getBranches();
        return \view('backend.pages.role.create', compact('permissions','branches'));
    }
    public function store(RoleAddRequest $request)
    {
        abort_if(!$this->setSlug('addRole')->hasPermission(), 403, 'You don\'t have permission!');
        try {
            $role = $this->roleService->createRole($request->validated());
            if(!$role)
                return redirect('role')->with('error', 'Failed to add Role');
        } catch (\Exception $exception) {
            return redirect()->back()->with('role', $exception->getMessage());
        }
        return redirect('/role')->with('success', 'Role add successfully');
    }

    public function validate_inputs(Request $request)
    {
        return $this->roleService->validateInputs($request->all());
    }
    public function edit($id )
    {
        abort_if(!$this->setSlug('editRole')->hasPermission(), 403, 'You don\'t have permission!');
        $role_info = $this->roleService->getRole($id);
        if($role_info=="Restore first")
            return redirect()->back()->with('error', $role_info);
        $permissions = $this->roleService->getAllPermissions($id);
        $branches = $this->roleService->getAllBranches($id);
        return \view('backend.pages.role.edit',compact('role_info','permissions','branches'));
    }
    public function update(RoleEditRequest $request)
    {
        abort_if(!$this->setSlug('editRole')->hasPermission(), 403, 'You don\'t have permission!');
        try {
            $role = $this->roleService->update($request->validated());
            if(!$role)
                return redirect('role')->with('error', 'Failed to update Role');
        } catch (\Exception $exception) {
            return redirect()->back()->with('role', $exception->getMessage());
        }
        return redirect('/role')->with('success', 'Role update successfully');
    }
    public function changeStatus($id)
    {
        abort_if(!$this->setSlug('manageRole')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            if($this->roleService->changeStatus($id))
                return redirect('role/')->with('success', "Role status changed successfully.");
            return redirect('role/')->with('error', "Role status not changed.");
        } catch (\Exception $exception) {
                return redirect()->back()->with('error', "OOPS! Role status could not be changed.");
        }
    }
    public function delete($id)
    {
        abort_if(!$this->setSlug('manageRole')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            if($this->roleService->delete($id))
                return redirect('role/')->with('success', "Role deleted successfully.");
            return redirect('role/')->with('error', "Role not deleted.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Role could not be deleted.");
        }
    }
    public function restore($id)
    {
        abort_if(!$this->setSlug('manageRole')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            if($this->roleService->restore($id))
                return redirect('role/')->with('success', "Role restored successfully.");
            return redirect('role/')->with('error', "Role not restored.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Role could not be restored.");
        }
    }
    public function validate_name(Request $request,$id)
    {
        return $this->roleService->validateName($request->all(),$id);
    }
}
