<?php

namespace App\Http\Controllers\LeaveApply;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveApplyAddRequest;
use App\Http\Requests\LeaveApplyUpdateRequest;
use Illuminate\Support\Facades\View;
use App\Services\LeaveApplyService;
use PHPMailer\PHPMailer\PHPMailer;  
use PHPMailer\PHPMailer\Exception;
use App\Traits\AuthorizationTrait;

class LeaveApplyController extends Controller
{
    use AuthorizationTrait;
    private $leaveApplyService;

    public function __construct(LeaveApplyService $leaveApplyService)
    {
        $this->leaveApplyService = $leaveApplyService;
        View::share('main_menu', 'LeaveApply');
    }

    public function getTableData()
    {
        return $this->leaveApplyService->getTableData();
    }

    public function apply()
    {
        View::share('sub_menu', 'Apply Leave');
        try {
            $leaveTypes = $this->leaveApplyService->getLeaveTypes();
            return view('backend.pages.leaveApply.apply', compact('leaveTypes'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function store(LeaveApplyAddRequest $request)
    {
        $this->checkAuthorization($request);
        
        try {
            $response = $this->leaveApplyService->storeLeaves($request);
            if($response) {
                if($this->leaveApplyService->LeaveApplicationEmail($request)) {
                    return redirect('leaveApply/manage')->with('success', 'Leave application submitted successfully.');
                } else {
                    return redirect('leaveApply/apply')->with('error', "Currently no HR is assigned in your branch");
                }
            } else {
                return redirect('leaveApply/apply')->with('error', $response);
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function manage()
    {
        View::share('sub_menu', 'Manage Leaves');
        return view('backend.pages.leaveApply.manage');
    }

    public function edit($id)
    {
        View::share('sub_menu', 'Manage Users');
        $leave = $this->leaveApplyService->editLeave($id);
        $leaveTypes = $this->leaveApplyService->getLeaveTypes();
        return view('backend.pages.leaveApply.edit', compact('leave', 'leaveTypes'));
    }

    public function update(LeaveApplyUpdateRequest $request, $id)
    {
        try {
            $response = $this->leaveApplyService->updateLeave($request, $id);
            if ($response === true) {
                return redirect('leaveApply/manage')->with('success', 'Leave updated successfully.');
            } else {
                return redirect('leaveApply/apply')->with('error', $response);
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

}
