<?php

namespace App\Http\Controllers\Leave;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LeaveTypeAddRequest;
use App\Http\Requests\LeaveTypeUpdateRequest;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Services\LeaveService;
use App\Traits\AuthorizationTrait;

class LeaveController extends Controller
{
    use AuthorizationTrait;
    private $leaveService;

    public function __construct(LeaveService $leaveService)
    {
        $this->leaveService = $leaveService;
        View::share('main_menu', 'System Settings');
        View::share('sub_menu', 'Leaves');
    }

    public function index()
    {
        $manageLeavePermission = $this->setId(auth()->user()->id)->setSlug('manageLeaves')->hasPermission();
        return \view('backend.pages.leave.index', compact('manageLeavePermission'));
    }

    public function create()
    {
        return \view('backend.pages.leave.create');
    }

    public function store(LeaveTypeAddRequest $request)
    {
        try {
            if(!($this->leaveService->storeLeave($request)))
                return redirect('leave')->with('error', 'Failed to add leave type');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect('leave/manage')->with('success', 'Leave type added successfully');
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

    public function update(LeaveTypeUpdateRequest $request, $id)
    {
        try {
            if(!$this->leaveService->updateLeave($request, $id))
                return redirect('leave')->with('error', 'Failed to update leave type');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect('/leave/manage')->with('success', 'Leave type updated successfully');
    }

    public function status($id)
    {
        try {
            $this->leaveService->updateStatus($id);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'You need to restore leave type first');
        }
        return redirect()->back()->with('success', 'Status has been changed');
    }

    public function destroy($id)
    {
        try {
            if(!$this->leaveService->destroyLeave($id))
                return redirect('leave')->with('error', 'Failed to delete leave type');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect()->back()->with('success', 'Leave type deleted successfully');
    }

    public function restore($id)
    {
        try {
            if(!$this->leaveService->restoreLeave($id))
                return redirect('leave')->with('error', 'Failed to restore leave type');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect()->back()->with('success', 'Leave type restored successfully');
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
        $leaves = $this->leaveService->manageLeave();
        return \view('backend.pages.leave.manage', compact('leaves'));
    }

    public function getTypeWiseTotalLeavesData(Request $request)
    {
        return $this->leaveService->getTypeWiseTotalLeavesData($request->year);
    }

    public function addTotalLeave(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'totalLeave'=>'numeric|min:0|max:180',
        ]);
        try {
            if(!$validator->fails()) {
                $this->leaveService->addTotalLeave($request, $id);
                return redirect()->back()->with('success', 'Total leave added successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to add total leave');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
