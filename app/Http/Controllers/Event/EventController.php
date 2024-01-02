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
use App\Services\DepartmentService;
use App\Traits\AuthorizationTrait;
use Illuminate\Support\Facades\Validator;


class EventController extends Controller
{
    use AuthorizationTrait;
    private $eventService, $userService, $branchService;

    public function __construct(EventService $eventService, UserService $userService, BranchService $branchService)
    {
        $this->eventService = $eventService;
        $this->userService = $userService;
        $this->branchService = $branchService;
        View::share('main_menu', 'Event');
    }

    public function manage()
    {
        View::share('sub_menu', 'Manage Events');
        $events = $this->eventService->getAllEvents();
        return view('backend.pages.event.manage', compact('events'));
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

    public function edit($id)
    {
        View::share('sub_menu', 'Manage Events');
        abort_if(!$this->setSlug('manageEvents')->hasPermission(), 403, 'You don\'t have permission!');
        try {
            $events = $this->eventService->getAllEvents($id);
            $branches = $this->branchService->getBranches();
            $allUsers = $this->userService->getAllUsers(null);
            $currentBranch = $this->eventService->getCurrentBranch($id);
            $currentDepartments = $this->eventService->getCurrentDepartments($id);
            $currentParticipants = $this->eventService->getCurrentUsers($id);
            return view('backend.pages.event.edit', compact('events', 'branches', 'allUsers', 'currentBranch', 'currentDepartments', 'currentParticipants'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function update(EventAddRequest $request, $id)
    {
        dd(1);
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
