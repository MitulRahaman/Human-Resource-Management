<?php

namespace App\Http\Controllers\Complaint;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComplaintAddRequest;
use App\Http\Requests\ComplaintUpdateRequest;
use App\Traits\AuthorizationTrait;
use App\Services\ComplaintService;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;


class ComplaintController extends Controller
{
    use AuthorizationTrait;
    private $complaintService, $userService;

    public function __construct(ComplaintService $complaintService)
    {
        $this->complaintService = $complaintService;
        View::share('main_menu', 'Complaints');
    }

    public function getTableData()
    {
        return $this->complaintService->getTableData();
    }

    public function manage()
    {
        View::share('sub_menu', 'Manage Complaints');
        return view('backend.pages.complaint.manage');
    }

    public function create()
    {
        View::share('sub_menu', 'Create Complaint');
        try {
            $users = $this->complaintService->getAllUsers();
            return view('backend.pages.complaint.create', compact('users'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function store(ComplaintAddRequest $request)
    {
        try {
            if ($this->complaintService->storeComplaint($request)) {
                return redirect('complaint/manage')->with('success', 'Complaint submitted successfully.');
            } else {
                return redirect('complaint/apply')->with('error', 'An error occurred!');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function edit($id)
    {
        View::share('sub_menu', 'Manage Complaints');
        $users = $this->complaintService->getAllUsers();
        $complaint = $this->complaintService->editComplaint($id);
        return view('backend.pages.complaint.edit', compact('complaint', 'users'));
    }

    public function update(ComplaintUpdateRequest $request, $id)
    {
        try {
            $this->complaintService->updateComplaint($request, $id);
            return redirect('complaint/manage')->with('success', 'Complaint updated successfully.');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function acknowledge(Request $request, $id)
    {
        try {
            $this->complaintService->acknowledgeComplaint($request,$id);
            return redirect()->back()->with('success', 'Complaint acknowledged');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $this->complaintService->rejectComplaint($request,$id);
            return redirect()->back()->with('success', 'Complaint rejected');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->complaintService->delete($id);
            return redirect()->back()->with('success', 'Complaint deleted');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
