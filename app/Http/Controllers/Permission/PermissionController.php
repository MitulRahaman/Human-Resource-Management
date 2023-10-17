<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionAddRequest;
use App\Http\Requests\PermissionEditRequest;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;

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

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'slug' => 'required|unique:permissions,slug|string|regex:/^[a-zA-Z][a-zA-Z0-9]*$/',
            'name' => 'required|unique:permissions,name',
        ]);

        // If validation fails, return a JSON response with validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {

            // If validation passes, create a new permission using permissionService
             $this->permissionService->createPermission($request->all());
            return response()->json(['success' => true]);
        }



    }
    public function changeStatus(Request $request)
    {
        try{
            $id = $request->permission_id;
            $permissionStatus = $this->permissionService->changeStatus($id);
                    return redirect('permission/');
            } catch (\Exception $exception) {
                return redirect()->back()->with('error', $exception->getMessage());

            }

    }
    public function delete(Request $request)
    {
        try{
            $id = (int)$request->delete_permission_id;
            $permissionStatus = $this->permissionService->delete($id);
            return redirect('permission/');

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());

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

            return redirect('permission/');

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());

        }
    }
    public function restore(Request $request)
    {
        try{
            $id = (int)$request->restore_permission_id;
            $permissionStatus = $this->permissionService->restore($id);

            return redirect('permission/');

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());

        }

    }

//    public function checkEdit(Request $request)
//    {
//        dd('cbjkbakjcbak');
//        dd('fajvckhabvk',$request->all());
////        $id = $this->route('id');
////        $id=(int)$id;
//        $validator = Validator::make($request->all(), [
//            'name' => 'unique:permissions,name',
////              'name'=>"required|unique:permissions,name,$id",
//        ]);
//
//        if ($validator->fails()){
//            return response()->json(['errors' => $validator->errors()], 400);
//        }
//
//
//        // If validation passes, return a success response
//        return response()->json(['success' => true]);
//    }

}
