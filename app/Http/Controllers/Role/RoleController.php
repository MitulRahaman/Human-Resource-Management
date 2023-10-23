<?php

namespace App\Http\Controllers\Role;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\RoleService;
use Illuminate\Support\Facades\View;
use App\Http\Requests\RoleAddRequest;




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

        $response = $this->roleService->validateInputs($request->all());
        return $response;

    }
    public function edit(int $id )
    {
        $role_info = $this->roleService->getRole($id);
        return \view('backend.pages.role.edit',compact('role_info'));
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
}
