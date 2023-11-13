<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\ProfileEditRequest;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserAddRequest;
use Illuminate\Support\Facades\View;
use App\Services\UserService;
use config;

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
        $emergency_contacts = $this->userService->getEmergencyContacts($user->id);
        $banking = $this->userService->getBankInfo($user->id);
//        dd($banking);
//        dd($emergency_contacts[0]->name);
//        dd($user->basicInfo);
        abort_if(!$user, 404);
        return \view('backend.pages.addUser.profile', compact('user', 'emergency_contacts', 'banking'));
    }
    public function edit($id)
    {
        View::share('sub_menu', 'Profile');
        $user = $this->userService->getUserInfo($id);
        $const_variable =config('variable_constants');
        $institutes = $this->userService->getInstitutes();
        $degree = $this->userService->getDegree();
        $bank = $this->userService->getBank();
        abort_if(!$user, 404);
        return \view('backend.pages.addUser.profileEdit', compact('user', 'bank','degree','institutes','const_variable'));
    }
    public function update(ProfileEditRequest $request)
    {
        try{
            $user = $this->userService->updateProfile($request->validated());
            if($user=="success")
                return redirect('user/profile/')->with('success', "Profile updated");
            else
                return redirect()->back()->with('error', "Profile could not be updated.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Profile could not be updated.");
        }

    }
}
