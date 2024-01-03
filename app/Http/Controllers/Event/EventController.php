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
            $availableDepartments = $this->eventService->getAvailableDepartments($id, $currentDepartments);
            $currentParticipants = $this->eventService->getCurrentUsers($id);
            $availableParticipants = $this->eventService->getAvailableUsers($id, $currentDepartments, $currentParticipants);
            return view('backend.pages.event.edit', compact('events', 'branches', 'allUsers', 'currentBranch', 'currentDepartments', 'availableDepartments', 'currentParticipants', 'availableParticipants'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function update(EventUpdateRequest $request, $id)
    {
        try {
            if($this->eventService->updateEvent($request, $id)) {
                return redirect('event/manage')->with('success', 'Event updated successfully.');
            } else {
                return redirect()->back()->with('error', 'An error occoured!');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->eventService->destroyEvent($id);
            return redirect('event/manage')->with('success', 'Event deleted');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

}
