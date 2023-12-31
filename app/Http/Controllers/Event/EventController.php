<?php

namespace App\Http\Controllers\Event;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventAddRequest;
use App\Http\Requests\EventUpdateRequest;
use Illuminate\Support\Facades\View;
use App\Services\EventService;
use App\Services\UserService;
use App\Services\BranchService;
use App\Services\LeaveApplyService;
use App\Traits\AuthorizationTrait;
use Illuminate\Support\Facades\Validator;


class EventController extends Controller
{
    use AuthorizationTrait;
    private $eventService, $userService, $branchService;

    public function __construct(EventService $eventService, UserService $userService, BranchService $branchService, LeaveApplyService $leaveApplyService)
    {
        $this->eventService = $eventService;
        $this->userService = $userService;
        $this->branchService = $branchService;
        $this->leaveApplyService = $leaveApplyService;
        View::share('main_menu', 'Event');
    }

    public function manage()
    {
        View::share('sub_menu', 'Manage Events');
        return view('backend.pages.event.manage');
    }

    public function create()
    {
        View::share('sub_menu', 'Create Event');
        abort_if(!$this->setSlug('manageEvents')->hasPermission(), 403, 'You don\'t have permission!');
        try {
            $branches = $this->branchService->getBranches();
            $allUsers = $this->userService->getAllUsers(null);
            return view('backend.pages.event.create', compact('branches', 'allUsers'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function getDeptPart(Request $request)
    {
        return $this->eventService->getDeptPart($request);
    }

    public function store(EventAddRequest $request)
    {
        if(!$this->leaveApplyService->validFileSize($request['photo'])) {
            return redirect('event/manage')->with('error', 'FileSize cannot exceed 25MB!');
        }
        try {
            if ($this->eventService->storeEvents($request)) {
                return redirect('event/manage')->with('success', 'Event Created successfully!');
            } else {
                return redirect('event/create')->with('error', 'An error occurred!');
            }

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }



    // public function edit($id)
    // {
    //     View::share('sub_menu', 'Manage Users');
    //     $leave = $this->leaveApplyService->editLeave($id);
    //     $leaveTypes = $this->leaveApplyService->getLeaveTypes();
    //     return view('backend.pages.leaveApply.edit', compact('leave', 'leaveTypes'));
    // }

    // public function update(LeaveApplyUpdateRequest $request, $id)
    // {
    //     try {
    //         $response = $this->leaveApplyService->updateLeave($request, $id);
    //         if ($response === true) {
    //             return redirect('leaveApply/manage')->with('success', 'Leave updated successfully.');
    //         } else {
    //             return redirect('leaveApply/apply')->with('error', $response);
    //         }
    //     } catch (\Exception $exception) {
    //         return redirect()->back()->with('error', $exception->getMessage());
    //     }
    // }


}
