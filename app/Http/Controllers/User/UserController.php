<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\ProfileEditRequest;
use App\Http\Controllers\Controller;
use App\Services\DepartmentService;
use App\Services\DesignationService;
use Illuminate\Http\Request;
use App\Http\Requests\UserAddRequest;
use Illuminate\Support\Facades\View;
use App\Services\UserService;

class UserController extends Controller
{
    private $userService, $departmentService, $designationService;

    public function __construct(UserService $userService, DepartmentService $departmentService, DesignationService $designationService)
    {
        $this->userService = $userService;
        $this->departmentService = $departmentService;
        $this->designationService = $designationService;
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
        return \view('backend.pages.user.create', compact('branches', 'organizations'));
    }

    public function manage()
    {
        return \view('backend.pages.user.manage');
    }

    public function getDeptDesg(Request $request)
    {
        return $this->userService->getDeptDesg($request);
    }

    public function store(UserAddRequest $request)
    {
        try {
            $response = $this->userService->storeUser($request);
            if ($response === true) {
                return redirect('user/manage')->with('success', 'User added successfully.');
            } else {
                return redirect('user/create')->with('error', $response);
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function editBasicInfo($id) : \Illuminate\View\View
    {
        View::share('sub_menu', 'Manage Users');
        $user = $this->userService->editUser($id);
        abort_if(!$user, 404);
        $branches = $this->userService->getBranches();
        $organizations = $this->userService->getOrganizations();
        $departments = $this->departmentService->getDepartments();
        $designations = $this->designationService->getDesignations();
        return \view('backend.pages.user.edit', compact('user', 'branches', 'organizations', 'departments', 'designations'));
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
        return \view('backend.pages.user.profileEdit', compact('user', 'bank','degree','institutes','const_variable'));
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

    public function updateData(Request $request)
    {
        return $this->userService->updateInputs($request);
    }
}
