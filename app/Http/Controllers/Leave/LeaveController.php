<?php

namespace App\Http\Controllers\Leave;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LeaveAddRequest;
use App\Http\Requests\LeaveUpdateRequest;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Services\LeaveService;

class LeaveController extends Controller
{
    private $leaveService;

    public function __construct(LeaveService $leaveService)
    {
        $this->leaveService = $leaveService;
        View::share('main_menu', 'System Settings');
        View::share('sub_menu', 'Leaves');
    }

    public function index()
    {
        $leaves = $this->leaveService->indexLeave();
        return \view('backend.pages.leave.index', compact('leaves'));
    }

    public function create()
    {
        return \view('backend.pages.leave.create');
    }

    public function store(LeaveAddRequest $request)
    {
        try {
            if(!is_object($this->leaveService->storeLeave($request)))
                return redirect('leave')->with('error', 'Failed to add leave');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect('leave')->with('success', 'Leave added successfully');
    }

    public function edit($id)
    {
        try {
            $data = $this->leaveService->editLeave($id);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return \view('backend.pages.leave.edit', compact('data'));
    }

    public function update(LeaveUpdateRequest $request, $id)
    {
        try {
            if(!$this->leaveService->updateLeave($request, $id))
                return redirect('leave')->with('error', 'Failed to update leave');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect('/leave')->with('success', 'Leave updated successfully');
    }

    public function status($id)
    {
        try {
            $this->leaveService->updateStatus($id);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'You need to restore leave first');
        }
        return redirect()->back()->with('success', 'Status has been changed');
    }

    public function destroy($id)
    {
        try {
            $this->leaveService->destroyLeave($id);  
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect()->back()->with('success', 'Leave deleted successfully');
    }

    public function restore($id)
    {
        try {
            $this->leaveService->restoreLeave($id);  
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect()->back()->with('success', 'Leave restored successfully');
    }

    public function verifyleave(Request $request)
    {
        try {
            return $this->leaveService->validateInputs($request);
        } catch(\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function updateleave(Request $request)
    {
        try {
            return $this->leaveService->updateInputs($request);
        } catch(\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function manage()
    {
        $leaves = $this->leaveService->indexLeave();
        return \view('backend.pages.leave.manage', compact('leaves'));
    }
}
