<?php

namespace App\Services;

use App\Jobs\MeetingJob;
use App\Repositories\MeetingRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use App\Traits\AuthorizationTrait;
use Illuminate\Support\Facades\Storage;
use Spatie\GoogleCalendar\Event;

class MeetingService
{
    use AuthorizationTrait;
    private $meetingRepository;

    public function __construct(MeetingRepository $meetingRepository)
    {
        $this->meetingRepository = $meetingRepository;
    }
    //    =============================start meeting======================
    public function getAllPlaces()
    {
        return $this->meetingRepository->getAllPlaces();
    }

    public function getAllUsers()
    {
        return $this->meetingRepository->getAllUsers();
    }

    public function create($data)
    {

        $start_time_carbonDate = Carbon::createFromFormat('H:i', $data['start_time']);
        $start_time_carbonDate->setTimezone($data['timezone']);
        $start_time_ms = $start_time_carbonDate->timestamp * 1000;

        $end_time_carbonDate = Carbon::createFromFormat('H:i', $data['end_time']);
        $end_time_carbonDate->setTimezone($data['timezone']);
        $end_time_ms = $end_time_carbonDate->timestamp * 1000;

        $this->meetingRepository->setTitle($data['title'])
            ->setAgenda($data['agenda'])
            ->setDate(Carbon::createFromFormat('d-m-Y', $data['date'])->format('Y-m-d'))
            ->setPlace($data['place'])
            ->setStartTime($start_time_ms)
            ->setEndTime($end_time_ms)
            ->setUrl($data['url']? $data['url']:'')
            ->setParticipants($data['participants'])
            ->setDescription($data['description'])
            ->setStatus(Config::get('variable_constants.meeting_status.pending'))
            ->setCreatedAt(date('Y-m-d H:i:s'));
        $not_available = $this->meetingRepository->checkMeetingAvailability();
        if($not_available) return false;
        $meeting = $this->meetingRepository->createMeeting();
        $event_name = $data['title'];
        $start_date_time =(Carbon::parse($data['date'].' '.$data['start_time']));
        $end_date_time = (Carbon::parse($data['date'].' '.$data['end_time']));
        if($meeting)
        {
            $to = $this->meetingRepository->getParticipantsEmails();
            $place_name = $this->meetingRepository->getMeetingPlaceName($data['place']);
            $data =[
                'info' =>  $data,
                'to' => $to,
                'place_name' => $place_name,
                'user_email' => auth()->user()->email,
                'user_name' => auth()->user()->full_name,
                'subject' => "Meeting : ".$data['title'],
            ];
            MeetingJob::dispatch($data);

            $attendees = array_map(function ($email) {
                return ['email' => $email];
            }, $to);

            $event = new Event;
            $event->name = $event_name;
            $event->startDateTime = $start_date_time;
            $event->endDateTime = $end_date_time;
            foreach ($attendees as $a)
            {
                $event->addAttendee($a);
            }
            $event->save();
            return true;
        }
        return false;
    }

    public function getMeeting($id)
    {
        return $this->meetingRepository->setId($id)->getMeeting();
    }

    public function getNote($id)
    {
        return $this->meetingRepository->setId($id)->getNote();
    }

    public function meetingMinute($id)
    {
        $result = $this->meetingRepository->setId($id)->meetingMinute();
        $data = [];
        if (sizeof($result) > 0) {
            foreach ($result as $key=>$row) {
                $user = $this->meetingRepository->getUser($key);
                $data[]=[
                  'employee_id' => $user->employee_id,
                    'name' => $user->full_name,
                    'notes' => preg_replace('#<[^>]+>#', ' ', $row)
                ];
            }
        }
        return $data;
    }

    public function getMeetingParticipants($id)
    {
        return $this->meetingRepository->setId($id)->getMeetingParticipants();
    }

    public function addNote($data)
    {
        return $this->meetingRepository->setId($data['id'])->setNotes($data['notes'])->addNote();
    }

    public function approveNote($id)
    {
        return $this->meetingRepository->setId($id)->approveNote();
    }

    public function update($data)
    {
        $prev_meeting = $this->meetingRepository->setId($data['id'])->getPrevMeeting();
//        $start_time_ms = (Carbon::createFromFormat('H:i', $data['start_time']))->timestamp * 1000;
//        $end_time_ms = (Carbon::createFromFormat('H:i', $data['end_time']))->timestamp * 1000;

        $start_time_carbonDate = Carbon::createFromFormat('H:i', $data['start_time']);
        $start_time_carbonDate->setTimezone('Asia/Dhaka');
        $start_time_ms = $start_time_carbonDate->timestamp * 1000;

        $end_time_carbonDate = Carbon::createFromFormat('H:i', $data['end_time']);
        $end_time_carbonDate->setTimezone('Asia/Dhaka');
        $end_time_ms = $end_time_carbonDate->timestamp * 1000;

        $this->meetingRepository->setTitle($data['title'])
            ->setAgenda($data['agenda'])
            ->setDate(Carbon::createFromFormat('d-m-Y', $data['date'])->format('Y-m-d'))
            ->setPlace($data['place'])
            ->setStartTime($start_time_ms)
            ->setEndTime($end_time_ms)
            ->setUrl($data['url']? $data['url']:'')
            ->setParticipants($data['participants'])
            ->setDescription($data['description'])
            ->setUpdatedAt(date('Y-m-d H:i:s'));
        $not_available = $this->meetingRepository->checkMeetingAvailability();
        if($not_available) return false;
        $meeting = $this->meetingRepository->updateMeeting();
        $event_name = $data['title'];
        $start_date_time =(Carbon::parse($prev_meeting->date.' '.$prev_meeting->start_time_formatted));
        $end_date_time = (Carbon::parse($prev_meeting->date.' '.$prev_meeting->end_time_formatted));
        $sdt =(Carbon::parse($data['date'].' '.$data['start_time']));
        $edt = (Carbon::parse($data['date'].' '.$data['end_time']));
        if($meeting)
        {
            $to = $this->meetingRepository->getParticipantsEmails();
            $place_name = $this->meetingRepository->getMeetingPlaceName($data['place']);
            $data =[
                'info' =>  $data,
                'to' => $to,
                'place_name' => $place_name,
                'user_email' => auth()->user()->email,
                'user_name' => auth()->user()->full_name,
                'subject' => "Meeting(Updated) : ".$data['title'],
            ];
            MeetingJob::dispatch($data);

            $attendees = array_map(function ($email) {
                return ['email' => $email];
            }, $to);

            $event = Event::get($start_date_time,$end_date_time)->first();
            if($event)
            {
                $event->name = $event_name;
                $event->startDateTime = $sdt;
                $event->endDateTime = $edt;
                foreach ($attendees as $a)
                {
                    $event->addAttendee($a);
                }

                $event->save();
            }

            return true;
        }
        return false;
    }

    public function fetchAttendeeData($id)
    {
        $result = $this->meetingRepository->setId($id)->getMeetingParticipants();
        $manageMeetingPermission = $this->setSlug('manageMeeting')->hasPermission();
        $user_id = auth()->user()->id;
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $id = $row->id;
                $employee_id = $row->employee_id;
                $name = $row->full_name;
                $meeting = $this->meetingRepository->getMeeting($id);
                if ($manageMeetingPermission ||  $user_id==$meeting->created_by || $this->isMeetingParticipant($id)) {
                    $notes = $row->notes ? $row->notes : "N/A";
                }
                else $notes = "N/A";
                $attendee_url = url('meeting/'.$id.'/notes/approve');
                $attendee_btn ="<a class=\"dropdown-item\" href=\"$attendee_url\">Approve Notes</a>";
                $note_url = url('meeting/'.$id.'/notes');
                $note_btn ="<a class=\"dropdown-item\" href=\"$note_url\">Notes</a>";

                if ($row->note_status == Config::get('variable_constants.note_status.pending')) {
                    $status = "<span class=\"badge badge-primary\">Pending</span>";
                }elseif ($row->note_status == Config::get('variable_constants.note_status.approved')){
                    $status = "<span class=\"badge badge-success\" >Approved</span>";
                }
                $action_btn = "<div class=\"col-sm-6 col-xl-4\">
                                    <div class=\"dropdown\">
                                        <button type=\"button\" class=\"btn btn-success dropdown-toggle\" id=\"dropdown-default-success\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Action
                                        </button>
                                        <div class=\"dropdown-menu font-size-sm\" aria-labelledby=\"dropdown-default-success\">";
                if ($user_id==$row->created_by && $row->notes && $row->note_status == Config::get('variable_constants.note_status.pending')) {
                    $action_btn .= " $attendee_btn ";
                }
                elseif($user_id==$row->user_id && $row->note_status == Config::get('variable_constants.note_status.pending'))
                    $action_btn .= " $note_btn ";
                else $action_btn = 'N/A';
                $action_btn .= "</div>
                                    </div>
                                </div>";
                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $employee_id);
                array_push($temp, $name);
                array_push($temp, $notes);
                array_push($temp, $status);
                array_push($temp, $action_btn);
                array_push($data, $temp);
            }
            return json_encode(array('data'=>$data));
        } else {
            return '{
                    "sEcho": 1,
                    "iTotalRecords": "0",
                    "iTotalDisplayRecords": "0",
                    "aaData": []
                }';
        }
    }

    public function isMeetingParticipant($id)
    {
        return $this->meetingRepository->isMeetingParticipant($id);
    }

    public function fetchData()
    {
        $result = $this->meetingRepository->getAllMeetingData();
        $manageMeetingPermission = $this->setSlug('manageMeeting')->hasPermission();
        $user_id = auth()->user()->id;
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $id = $row->id;
                $title = $row->title;
                $agenda = $row->agenda;
                $description = $row->description;
                $place = $row->place;
                $date = $row->date;
                $start_time = $row->start_time_formatted;
                $end_time = $row->end_time_formatted;
                $created_at = $row->created_at;
                $attendee_url = url('meeting/'.$id.'/attendee');
                $attendee_btn ="<a class=\"dropdown-item\" href=\"$attendee_url\">Attendee</a>";
                $meeting_minute_url = url('meeting/'.$id.'/meeting_minute');
                $meeting_minute_btn ="<a class=\"dropdown-item\" href=\"$meeting_minute_url\">Meeting Minute</a>";
                $edit_url = route('edit', ['id'=>$id]);
                $edit_btn = "<a class=\"dropdown-item\" href=\"$edit_url\">Edit</a>";
                $action_btn = "<div class=\"col-sm-6 col-xl-4\">
                                    <div class=\"dropdown\">
                                        <button type=\"button\" class=\"btn btn-success dropdown-toggle\" id=\"dropdown-default-success\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Action
                                        </button>
                                        <div class=\"dropdown-menu font-size-sm\" aria-labelledby=\"dropdown-default-success\">";
                if (($manageMeetingPermission ||  $user_id==$row->created_by) && $row->status == Config::get('variable_constants.meeting_status.pending')) {
                    $action_btn .= " $edit_btn ";
                }
                if ($manageMeetingPermission ||  $user_id==$row->created_by || $this->isMeetingParticipant($id)) {
                    $action_btn .= " $meeting_minute_btn ";
                }
                $action_btn .= " $attendee_btn " ;
                $action_btn .= "</div>
                                    </div>
                                </div>";
                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $title);
                array_push($temp, $agenda);
                array_push($temp, $description);
                array_push($temp, $place);
                array_push($temp, $date);
                array_push($temp, $start_time);
                array_push($temp, $end_time);
//                array_push($temp, $meeting_minutes);
                array_push($temp, $created_at);
                array_push($temp, $action_btn);
                array_push($data, $temp);
            }
            return json_encode(array('data'=>$data));
        } else {
            return '{
                    "sEcho": 1,
                    "iTotalRecords": "0",
                    "iTotalDisplayRecords": "0",
                    "aaData": []
                }';
        }
    }
    //    =============================end meeting======================

//    =============================start meeting places======================

    public function validate_inputs_meeting_place($data)
    {
        $this->meetingRepository->setName($data['name']);
        $is_name_exists = $this->meetingRepository->isNameExists();
        $name_msg = $is_name_exists ? 'Name already taken' : null;
        if(!$data['name']) $name_msg = 'Name is required';
        if ( $is_name_exists) {
            return [
                'success' => false,
                'name_msg' => $name_msg,
            ];
        } else {
            return [
                'success' => true,
                'name_msg' => $name_msg,
            ];
        }
    }

    public function createMeetingPlace($data)
    {
        return $this->meetingRepository->setName($data['name'])
            ->setStatus(Config::get('variable_constants.activation.active'))
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->createMeetingPlace();
    }

    public function getMeetingPlace($id)
    {
        return $this->meetingRepository->setId($id)->getMeetingPlace();
    }

    public function validate_name_meeting_place($data,$id)
    {
        $this->meetingRepository->setName($data['name'])->setId($id);
        $is_name_exists = $this->meetingRepository->isNameUnique( );
        $name_msg = $is_name_exists ? 'Name already taken' : null;
        if(!$data['name']) $name_msg = 'Name is required';
        if ( $is_name_exists) {
            return [
                'success' => false,
                'name_msg' => $name_msg,
            ];
        } else {
            return [
                'success' => true,
                'name_msg' => $name_msg,
            ];
        }
    }

    public function updateMeetingPlace($data)
    {
        return $this->meetingRepository->setId($data['id'])
            ->setName($data['name'])
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->updateMeetingPlace();
    }

    public function changeMeetingPlaceStatus($id)
    {
        return $this->meetingRepository->setId($id)->changeMeetingPlaceStatus();
    }

    public function restoreMeetingPlace($id)
    {
        return $this->meetingRepository->setId($id)->restoreMeetingPlace();
    }

    public function deleteMeetingPlace($id)
    {
        return $this->meetingRepository->setId($id)->setDeletedAt(date('Y-m-d H:i:s'))->deleteMeetingPlace();
    }

    public function fetchMeetingPlaceData()
    {
        $result = $this->meetingRepository->getAllMeetingPlaceData();
        $manageMeetingPlacePermission = $this->setSlug('manageMeetingPlace')->hasPermission();
        if ($result->count() > 0) {
            $data = array();

            foreach ($result as $key=>$row) {
                $id = $row->id;
                $name = $row->name;
                $created_at = $row->created_at;
                if ($row->status == Config::get('variable_constants.activation.active')) {
                    $status = "<span class=\"badge badge-success\">Active</span>";
                    $status_msg = "Deactivate";
                }else{
                    $status = "<span class=\"badge badge-danger\" >Inactive</span>";
                    $status_msg = "Activate";
                }
                $edit_url = url('meeting_place/'.$id.'/edit');
                $edit_btn = "<a class=\"dropdown-item\" href=\"$edit_url\">Edit</a>";
                $toggle_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_status_modal(\"$id\", \"$status_msg\")'> $status_msg </a>";
                if ($row->deleted_at) {
                    $toggle_delete_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_restore_modal(\"$id\", \"$name\")'>Restore</a>";
                } else {
                    $toggle_delete_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_delete_modal(\"$id\", \"$name\")'>Delete</a>";
                }
                $action_btn = "<div class=\"col-sm-6 col-xl-4\">
                                    <div class=\"dropdown\">
                                        <button type=\"button\" class=\"btn btn-success dropdown-toggle\" id=\"dropdown-default-success\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Action
                                        </button>
                                        <div class=\"dropdown-menu font-size-sm\" aria-labelledby=\"dropdown-default-success\">";
                $action_btn .= "$edit_btn
                $toggle_btn
                $toggle_delete_btn
                ";
                $action_btn .= "</div>
                                    </div>
                                </div>";
                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $name);
                array_push($temp, $status);
                if ($row->deleted_at) {
                    array_push($temp, ' <span class="badge badge-danger" >Yes</span>');
                } else {
                    array_push($temp, ' <span class="badge badge-success">No</span>');
                }
                array_push($temp, $created_at);
                if($manageMeetingPlacePermission)
                    array_push($temp, $action_btn);
                else
                    array_push($temp, 'N/A');
                array_push($data, $temp);
            }
            return json_encode(array('data'=>$data));
        } else {
            return '{
                    "sEcho": 1,
                    "iTotalRecords": "0",
                    "iTotalDisplayRecords": "0",
                    "aaData": []
                }';
        }
    }
    //    =============================end meeting places======================
}
