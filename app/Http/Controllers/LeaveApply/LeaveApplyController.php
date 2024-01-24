<?php

namespace App\Http\Controllers\LeaveApply;



use Excel;
use Illuminate\Http\Request;
use App\Exports\ExportLeaves;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveApplyAddRequest;
use App\Http\Requests\LeaveApplyUpdateRequest;
use Illuminate\Support\Facades\View;
use App\Services\LeaveApplyService;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Traits\AuthorizationTrait;
use Illuminate\Support\Facades\Validator;


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
        abort_if(!$this->setSlug('applyLeave')->hasPermission(), 403, 'You don\'t have permission!');
        try {
            $leaveTypes = $this->leaveApplyService->getLeaveTypes();
            return view('backend.pages.leaveApply.apply', compact('leaveTypes'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function recommendLeave(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'remarks' => 'required',
            ]);
            if(!$validator->fails()) {
                $this->leaveApplyService->recommendLeave($request->all(),$id);
                return redirect()->back()->with('success', 'Recommend');
            } else {
                return redirect()->back()->with('error', 'Remarks required');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function approveLeave(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'remarks' => 'nullable',
            ]);
            if($validator)
            {
                if($this->leaveApplyService->approveLeave($request,$id)) {
                    return redirect()->back()->with('success', 'Leave approved');
                } else {
                    return redirect()->back()->with('error', 'Line Manager is not reommended');
                }
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function rejectLeave(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'remarks' => 'nullable',
            ]);
            if($validator) {
                $this->leaveApplyService->rejectLeave($request->all(),$id);
                return redirect()->back()->with('success', 'Leave rejected');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function cancelLeave($id)
    {
        try {
            $this->leaveApplyService->cancelLeave($id);
            return redirect()->back()->with('success', 'Leave canceled');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->leaveApplyService->delete($id);
            return redirect()->back()->with('success', 'Leave deleted');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function store(LeaveApplyAddRequest $request)
    {
        if($request->totalLeave == null) {
            return redirect('leaveApply/apply')->with('error', 'Select Leave date again');
        }
        if(!$this->leaveApplyService->validFileSize($request['photo'])) {
            return redirect('leaveApply/apply')->with('error', 'FileSize cannot exceed 25MB!');
        }
        try {
            if ($this->leaveApplyService->storeLeaves($request)) {
                return redirect('leaveApply/manage')->with('success', 'Leave application submitted successfully.');
            } else {
                return redirect('leaveApply/apply')->with('error', 'An error occurred!');
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

    public function leaveReports()
    {
        abort_if(!$this->setSlug('viewLeaveReports')->hasPermission(), 403, 'You don\'t have permission!');
        View::share('sub_menu', 'Yearly Leaves Data');
        $leaveTypes = $this->leaveApplyService->getLeaveTypes();
        return view('backend.pages.leaveApply.leaveReports', compact('leaveTypes'));
        // return Excel::download(new ExportLeaves, 'users.xlsx');
    }

    public function getReportData()
    {
        return $this->leaveApplyService->getReportData();
    }

}
