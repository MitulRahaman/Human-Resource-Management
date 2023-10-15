<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionAddRequest;
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
        $data = [
            'slug' => $request->input('name'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ];

        $permission = $this->permissionService->createPermission($data);


        return redirect('permission/');
    }
    public function changeStatus(Request $request)
    {

        $id = $request->permission_id;
        $permissionStatus = $this->permissionService->changeStatus($id);
        return redirect('permission/');
    }
    public function delete(Request $request)
    {
        $id = (int)$request->delete_permission_id;
        $permissionStatus = $this->permissionService->delete($id);
        return redirect('permission/');
    }
    public function edit(int $id )
    {


        return redirect('permission/{$id}/edit');
    }

}
