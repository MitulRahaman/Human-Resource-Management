<?php

namespace App\Services;

use App\Jobs\MeetingJob;
use App\Repositories\MeetingRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use App\Traits\AuthorizationTrait;
use Illuminate\Support\Facades\Storage;

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
        $start_time_ms = (Carbon::createFromFormat('H:i', $data['start_time']))->timestamp * 1000-21600000;
        $end_time_ms = (Carbon::createFromFormat('H:i', $data['end_time']))->timestamp * 1000-21600000;
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
            return true;
        }
        return false;
    }

    public function getMeeting($id)
    {
        return $this->meetingRepository->setId($id)->getMeeting();
    }

    public function update($data)
    {
        $start_time_ms = (Carbon::createFromFormat('H:i', $data['start_time']))->timestamp * 1000-21600000;
        $end_time_ms = (Carbon::createFromFormat('H:i', $data['end_time']))->timestamp * 1000-21600000;
        $this->meetingRepository->setId($data['id'])
            ->setTitle($data['title'])
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
            return true;
        }
        return false;
    }

    public function complete($id, $data)
    {
        $extension = $data['meeting_minutes']->getClientOriginalExtension();
        $file_name = random_int(00001, 99999).'.'.$extension;
        $file_path = 'meeting/'.$file_name;
        Storage::disk('public')->put($file_path, file_get_contents($data['meeting_minutes']));
        return $this->meetingRepository->setId($id)->setMeetingMinutes($file_name)->setUpdatedAt(date('Y-m-d H:i:s'))->complete();
    }

    public function fetchData()
    {
        $result = $this->meetingRepository->getAllMeetingData();
        $manageMeetingPermission = $this->setSlug('manageMeeting')->hasPermission();
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
                $meeting_minutes = $row->meeting_minutes? "Download":"N/A";
                if ($manageMeetingPermission && $row->meeting_minutes) {
                    $meeting_minutes = "<a href='" . asset('storage/meeting/' . $row->meeting_minutes) . "' download>Download</a>";
                }
                $created_at = $row->created_at;
                if ($row->status == Config::get('variable_constants.meeting_status.pending')) {
                    $status = "<span class=\"badge badge-primary\">Pending</span>";
                }elseif ($row->status == Config::get('variable_constants.meeting_status.completed')){
                    $status = "<span class=\"badge badge-success\" >Completed</span>";
                }
                $edit_url = route('edit', ['id'=>$id]);
                $edit_btn = "<a class=\"dropdown-item\" href=\"$edit_url\">Edit</a>";
                $complete_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_complete_modal(\"$id\", \"$title\")'> Complete Meeting</a>";
                $action_btn = "<div class=\"col-sm-6 col-xl-4\">
                                    <div class=\"dropdown\">
                                        <button type=\"button\" class=\"btn btn-success dropdown-toggle\" id=\"dropdown-default-success\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Action
                                        </button>
                                        <div class=\"dropdown-menu font-size-sm\" aria-labelledby=\"dropdown-default-success\">";
                if ($manageMeetingPermission && $row->status == Config::get('variable_constants.meeting_status.pending')) {
                    $action_btn .= "$edit_btn $complete_btn ";
                }
                else
                    $action_btn = 'N/A';
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
                array_push($temp, $status);
                array_push($temp, $meeting_minutes);
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
                $edit_url = route('edit', ['id'=>$id]);
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