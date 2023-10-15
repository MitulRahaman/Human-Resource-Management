<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
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
}
