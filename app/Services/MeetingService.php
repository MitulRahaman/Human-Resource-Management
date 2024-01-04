<?php

namespace App\Services;

use App\Repositories\MeetingRepository;
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
