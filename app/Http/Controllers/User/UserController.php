<?php

namespace App\Http\Controllers\User;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserAddRequest;
use Illuminate\Support\Facades\View;
use App\Services\UserService;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        View::share('main_menu', 'Users');
        View::share('sub_menu', 'Add User');
    }

    public function getTableData()
    {
        return $this->userService->getTableData();
    }

    public function create()
    {
        $branches = $this->userService->getBranches();
        $organizations = $this->userService->getOrganizations();
        return \view('backend.pages.addUser.create', compact('branches', 'organizations'));
    }

    public function manage()
    {
        return \view('backend.pages.addUser.manage');
    }

    public function getDeptDesg(Request $request)
    {
        return $this->userService->getDeptDesg($request);
    }

    public function store(UserAddRequest $request)
    {
        try {
            if(!($this->userService->storeUser($request)))
                return redirect('user/create')->with('error', 'Failed to add user');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect('user/create')->with('success', 'User added successfully');
    }

    public function verifydata(Request $request)
    {
        try {
            return $this->userService->validateInputs($request);
        } catch(\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function show(int $id=null)
    {
        View::share('sub_menu', 'Profile');
        $user = $this->userService->getUserInfo($id);
        //dd($user);
        abort_if(!$user, 404);
        return \view('backend.pages.addUser.profile', compact('user'));
    }
}
