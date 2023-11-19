<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\ProfileEditRequest;
use App\Http\Controllers\Controller;
use App\Services\DepartmentService;
use App\Services\DesignationService;
use Illuminate\Http\Request;
use App\Http\Requests\UserAddRequest;
use App\Http\Requests\UserUpdateRequest;
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
    }

    public function getTableData()
    {
        return $this->userService->getTableData();
    }

    public function create()
    {
        View::share('sub_menu', 'Add User');
        $branches = $this->userService->getBranches();
        $organizations = $this->userService->getOrganizations();
        return \view('backend.pages.user.create', compact('branches', 'organizations'));
    }

    public function manage()
    {
        View::share('sub_menu', 'Manage Users');
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
    
    public function editBasicInfo($id)
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

    public function edit($id)
    {
        View::share('sub_menu', 'Manage Users');
        try {
            $branches = $this->userService->getBranches();
            $organizations = $this->userService->getOrganizations();
            $data = $this->userService->editUser($id);
            $currentBranchName = $this->userService->getCurrentBranchName($data->branch_id);
            $currentDepartmentName = $this->userService->getCurrentDepartmentName($data->department_id);
            $currentDesignationName = $this->userService->getCurrentDesignationName($data->designation_id);
            $currentOrganizationName = $this->userService->getCurrentOrganizationName($data->last_organization_id);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return \view('backend.pages.user.edit', compact('data', 'branches', 'organizations', 'currentBranchName', 'currentDepartmentName', 'currentDesignationName', 'currentOrganizationName'));
    }

    public function update(UserUpdateRequest $request, $id)
    {
        try {
            if(!$this->userService->updateUser($request, $id))
                return redirect('user/manage')->with('error', 'Failed to update user');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect('user/manage')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        try {
            if(!$this->userService->destroyUser($id))
                return redirect('user')->with('error', 'Failed to delete user');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect()->back()->with('success', 'User deleted successfully');
    }

    public function restore($id)
    {
        try {
            if(!$this->userService->restoreUser($id))
                return redirect('user')->with('error', 'Failed to restore user');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect()->back()->with('success', 'User restored successfully');
    }

    public function changeStatus($id)
    {
        try {
            $this->userService->updateStatus($id);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'You need to restore the user first');
        }
        return redirect()->back()->with('success', 'Status has been changed');
    }

    public function verifyUser(Request $request)
    {
        try {
            return $this->userService->validateInputs($request);
        } catch(\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function updateUser(Request $request)
    {
        try {
            return $this->userService->updateInputs($request);
        } catch(\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    

    // -----basic info part-----

    public function deleteAcademicInfo($id)
    {
        try {
            if(!$this->userService->deleteAcademicInfo($id))
                return redirect()->back()->with('error', 'Failed to update delete academic info');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect()->back()->with('success', 'Academic info deleted successfully');
    }

    public function show($id=null)
    {
        View::share('sub_menu', 'Profile');
        $user = $this->userService->getUserInfo($id);
        $emergency_contacts = $this->userService->getEmergencyContacts($user->id);
        $banking = $this->userService->getBankInfo($user->id);
        abort_if(!$user, 404);
        return \view('backend.pages.user.profile', compact('user', 'emergency_contacts', 'banking'));
    }
    public function editData($id)
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
    public function updateData(ProfileEditRequest $request)
    {
        try{
            $user = $this->userService->updateProfile($request->validated());
            if(!$user)
                return redirect()->back()->with('error', "Profile could not be updated.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect('user/manage')->with('success', 'User profile updated successfully');
    }

}
