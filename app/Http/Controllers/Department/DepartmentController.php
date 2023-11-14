<?php

namespace App\Http\Controllers\Department;

use Validator;
use App\Models\Branch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\DepartmentAddRequest;
use App\Http\Requests\DepartmentUpdateRequest;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Services\DepartmentService;

class DepartmentController extends Controller
{
    private $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
        View::share('main_menu', 'System Settings');
        View::share('sub_menu', 'Departments');
    }

    public function index()
    {
        $departments = $this->departmentService->indexDepartment();
        return \view('backend.pages.department.index', compact('departments'));
    }

    public function create()
    {
        $branches = $this->departmentService->createDepartment();
        return \view('backend.pages.department.create', compact('branches'));
    }

    public function store(DepartmentAddRequest $request)
    {
        try {
            if(!($this->departmentService->storeDepartment($request)))
                return redirect('department')->with('error', 'Failed to add department');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect('department')->with('success', 'department added successfully');
    }

    public function edit($id)
    {
        try {
            $departments = $this->departmentService->indexDepartment();
            $current_data = $this->departmentService->editDepartment($id, $departments);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        $data = $current_data[0];
        $branch_name = $current_data[1];

        $branches = $this->departmentService->createDepartment();

        return \view('backend.pages.department.edit', compact('data', 'branches', 'branch_name'));
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
