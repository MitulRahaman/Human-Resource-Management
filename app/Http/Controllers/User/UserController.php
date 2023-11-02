<?php

namespace App\Http\Controllers\User;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Services\UserService;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        View::share('main_menu', 'Users');
        View::share('sub_menu', 'AddUser');
    }

    public function addUserIndex()
    {
        return \view('backend.pages.addUser.index');
    }

    public function getTableData()
    {
        return $this->userService->getTableData();
    }
}