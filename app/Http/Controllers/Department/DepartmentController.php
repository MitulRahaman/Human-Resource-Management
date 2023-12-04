<?php

namespace App\Http\Controllers\Department;

use App\Services\BranchService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\DepartmentAddRequest;
use App\Http\Requests\DepartmentUpdateRequest;
use Illuminate\Support\Facades\View;
use App\Services\DepartmentService;
use App\Traits\AuthorizationTrait;

class DepartmentController extends Controller
{
    use AuthorizationTrait;
    private $departmentService, $branchService;

    public function __construct(DepartmentService $departmentService, BranchService $branchService)
    {
        $this->departmentService = $departmentService;
        $this->branchService = $branchService;
        View::share('main_menu', 'System Settings');
        View::share('sub_menu', 'Departments');
    }

    public function index()
    {
        $hasDepartmentManagePermission = $this->setId(auth()->user()->id)->setSlug('manageDepartments')->hasPermission();
        $departments = $this->departmentService->indexDepartment();
        return \view('backend.pages.department.index', compact('departments', 'hasDepartmentManagePermission'));
    }

    public function create()
    {
        $branches = $this->branchService->getBranches();
        return \view('backend.pages.department.create', compact('branches'));
    }

    public function store(DepartmentAddRequest $request)
    {
        try {
            $response = $this->departmentService->storeDepartment($request);
            if ($response === true) {
                return redirect('department')->with('success', 'Department added successfully.');
            } else {
                return redirect('department')->with('error', $response);
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function edit($id)
    {
        $data = $this->departmentService->departmentInfo($id);
        abort_if(!$data, 404);
        $branches = $this->branchService->getBranches();
        return \view('backend.pages.department.edit', compact('data', 'branches'));
    }

    public function update(DepartmentUpdateRequest $request, $id)
    {
        try {
            if(!$this->departmentService->updateDepartment($request, $id))
                return redirect('department')->with('error', 'Failed to update department');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect('/department')->with('success', 'department updated successfully');
    }

    public function status($id)
    {
        try {
            $this->departmentService->updateStatus($id);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'You need to restore the branch first');
        }
        return redirect()->back()->with('success', 'Status has been changed');
    }

    public function destroy($id)
    {
        try {
            if(!$this->departmentService->destroyDepartment($id))
                return redirect('department')->with('error', 'Failed to delete department');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect()->back()->with('success', 'Department deleted successfully');
    }

    public function restore($id)
    {
        try {
            if(!$this->departmentService->restoreDepartment($id))
                return redirect('department')->with('error', 'Failed to restore department');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect()->back()->with('success', 'Department restored successfully');
    }

    public function verifydept(Request $request)
    {
        try {
            return $this->departmentService->validateInputs($request);
        } catch(\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function updatedept(Request $request)
    {
        try {
            return $this->departmentService->updateInputs($request);
        } catch(\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
