<?php

namespace App\Http\Controllers\Meeting;

use App\Http\Requests\MeetingAddRequest;
use App\Http\Requests\MeetingEditRequest;
use App\Http\Requests\MeetingPlaceAddRequest;
use App\Http\Requests\MeetingPlaceEditRequest;
use App\Services\MeetingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Traits\AuthorizationTrait;
//use Barryvdh\DomPDF\Facade as PDF;

class MeetingController extends Controller
{
    use AuthorizationTrait;
    private $meetingService;

    public function __construct(MeetingService $meetingService)
    {
        $this->meetingService = $meetingService;
        View::share('main_menu', 'System Settings');
    }

//    ========================= start meeting ==========================
    public function index()
    {
        View::share('sub_menu', 'Manage Meetings');
        $addMeetingPermission = $this->setSlug('addMeeting')->hasPermission();
        return \view('backend.pages.meeting.index', compact('addMeetingPermission'));
    }

    public function fetchData()
    {
        return $this->meetingService->fetchData();
    }
    public function fetchAttendeeData($id)
    {
        return $this->meetingService->fetchAttendeeData($id);
    }

    public function create()
    {
        View::share('sub_menu', 'Add Meeting');
        $places = $this->meetingService->getAllPlaces();
        $participants = $this->meetingService->getAllUsers();
        return \view('backend.pages.meeting.create', compact('places','participants'));
    }

    public function store(MeetingAddRequest $request)
    {
        try{
            $response = $this->meetingService->create($request->validated());
            if ($response) {
                return redirect('meeting/')->with('success', 'Meeting saved successfully.');
            } else {
                return redirect('meeting/')->with('error', 'Meeting not saved');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function edit($id)
    {
        View::share('sub_menu', 'Manage Meetings');
        $meeting = $this->meetingService->getMeeting($id);
        if($meeting && !is_null($meeting->deleted_at))
            return redirect()->back()->with('error', 'Invalid meeting');
        $places = $this->meetingService->getAllPlaces();
        $participants = $this->meetingService->getAllUsers();
        return \view('backend.pages.meeting.edit',compact('meeting','places', 'participants'));
    }

    public function attendee($id)
    {
        View::share('sub_menu', 'Manage Meetings');
        $meeting = $this->meetingService->getMeeting($id);
        return \view('backend.pages.meeting.attendee',compact('meeting','id'));
    }

    public function viewAddNote($id)
    {
        View::share('sub_menu', 'Manage Meetings');
        $note = $this->meetingService->getNote($id);
        return \view('backend.pages.meeting.notes',compact('id', 'note'));
    }

    public function meetingMinute($id)
    {
        View::share('sub_menu', 'Manage Meetings');
            $meeting_minute = $this->meetingService->meetingMinute($id);
            $meeting = $this->meetingService->getMeeting($id);
            $data = [
                'meeting_minute' => $meeting_minute,
                'meeting_info' => $meeting,
                'date' => date('m/d/Y')
            ];

            $pdf = Pdf::loadView('backend.pages.meeting.meetingMinute', $data);
            return $pdf->download('MeetingMinutes.pdf');
    }

    public function addNote(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:meeting_participants,id',
                'notes' => 'required'
            ]);
            if($validator->fails()) {
                return redirect()->back()->with('error', 'Note not added');
            }
            $note = $this->meetingService->addNote($validator->validated());
            if($note)
                return redirect('meeting')->with('success', 'Note added');
            return redirect()->back()->with('error', 'Note not added');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function approveNote($id)
    {
        $note = $this->meetingService->approveNote($id);
        if($note)
           return redirect()->back()->with('success', 'Note approved');
        return redirect()->back()->with('error', 'Note not approved');
    }

    public function update(MeetingEditRequest $request)
    {
        try {
            $meeting = $this->meetingService->update($request->validated());
            if(!$meeting)
                return redirect('meeting')->with('error', 'Failed to update meeting');
            return redirect('/meeting')->with('success', 'Meeting updated successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

//    ========================= end meeting ==========================
//    ========================= start meeting place ==========================
    public function meetingPlaceIndex()
    {
        View::share('sub_menu', 'Meeting Places');
        $addMeetingPlacePermission = $this->setSlug('addMeetingPlace')->hasPermission();
        return \view('backend.pages.meeting.meetingPlaceIndex', compact('addMeetingPlacePermission'));
    }

    public function fetchMeetingPlaceData()
    {
        return $this->meetingService->fetchMeetingPlaceData();
    }

    public function createMeetingPlace()
    {
        abort_if(!$this->setSlug('addMeetingPlace')->hasPermission(), 403, 'You don\'t have permission!');
        View::share('sub_menu', 'Meeting Places');
        return \view('backend.pages.meeting.createMeetingPlace');
    }

    public function validate_inputs_meeting_place(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if(!$validator->fails())
            return $this->meetingService->validate_inputs_meeting_place($request->all());
        return redirect()->back()->with('error', 'Name is Required to validate');
    }

    public function storeMeetingPlace(MeetingPlaceAddRequest $request)
    {
        abort_if(!$this->setSlug('addMeetingPlace')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            $response = $this->meetingService->createMeetingPlace($request->validated());
            if (is_int($response)) {
                return redirect('meeting_place/')->with('success', 'Meeting Place saved successfully.');
            } else {
                return redirect()->back()->with('error', $response);
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function editMeetingPlace($id )
    {
        abort_if(!$this->setSlug('editMeetingPlace')->hasPermission(), 403, 'You don\'t have permission!');
        View::share('sub_menu', 'Meeting Places');
        $meeting_place = $this->meetingService->getMeetingPlace($id);
        if($meeting_place && !is_null($meeting_place->deleted_at))
            return redirect()->back()->with('error', 'Restore first');
        return \view('backend.pages.meeting.editMeetingPlace',compact('meeting_place'));
    }

    public function validate_name_meeting_place(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if(!$validator->fails())
            return $this->meetingService->validate_name_meeting_place($request->all(),$id);
        return redirect()->back()->with('error', 'Name is Required to validate');
    }

    public function updateMeetingPlace(MeetingPlaceEditRequest $request)
    {
        abort_if(!$this->setSlug('editMeetingPlace')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            if($this->meetingService->updateMeetingPlace($request->validated()))
                return redirect('meeting_place/')->with('success', "Meeting place updated successfully.");
            return redirect('meeting_place/')->with('success', "Meeting place not updated.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function changeMeetingPlaceStatus($id)
    {
        abort_if(!$this->setSlug('manageMeetingPlace')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            $meeting_place = $this->meetingService->getMeetingPlace($id);
            if($meeting_place && !is_null($meeting_place->deleted_at))
                return redirect()->back()->with('error', 'Restore first');
            elseif($this->meetingService->changeMeetingPlaceStatus($id))
                return redirect('meeting_place/')->with('success', "Meeting Place status changed successfully.");
            return redirect('meeting_place/')->with('error', "Meeting Place status not changed.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function deleteMeetingPlace($id)
    {
        abort_if(!$this->setSlug('manageMeetingPlace')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            if($this->meetingService->deleteMeetingPlace($id))
                return redirect('meeting_place/')->with('success', "Meeting Place deleted successfully.");
            return redirect('meeting_place/')->with('error', "Meeting Place not deleted.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function restoreMeetingPlace($id)
    {
        abort_if(!$this->setSlug('manageMeetingPlace')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            if($this->meetingService->restoreMeetingPlace($id))
                return redirect('meeting_place/')->with('success', "Meeting Place restored successfully.");
            return redirect('meeting_place/')->with('success', "Meeting Place not restored.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
//    ========================= end meeting place ==========================
}
