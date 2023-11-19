<?php

namespace App\Services;

use App\Repositories\LeaveApplyRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class LeaveApplyService
{
    private $leaveApplyRepository;

    public function __construct(LeaveApplyRepository $leaveApplyRepository)
    {
        $this->leaveApplyRepository = $leaveApplyRepository;
    }

    public function getLeaveTypes()
    {
        return $this->leaveApplyRepository->getLeaveTypes();
    }

    public function storeLeaves($data)
    {
        return $this->leaveApplyRepository->storeLeaves($data);
    }

    public function editLeave($id)
    {
        return $this->leaveApplyRepository->setId($id)->getLeaveInfo();
    }

    public function updateLeave($data, $id)
    {
        return $this->leaveApplyRepository->setId($id)->updateLeave($data);
    }

    public function getTableData()
    {
        $result = $this->leaveApplyRepository->getTableData();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $id = $row->id;
                $leave_type = $row->name;
                $start_date = date("d-m-Y", strtotime($row->start_date));
                $end_date = date("d-m-Y", strtotime($row->end_date));
                $total_leave = $row->total;
                $reason = $row->reason;
                $remarks = $row->remarks;

                if ($row->deleted_at) {
                    $toggle_delete_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_restore_modal(\"$id\", \"$leave_type\")'>Restore</a>";
                } else {
                    $toggle_delete_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_delete_modal(\"$id\", \"$leave_type\")'>Delete</a>";
                }

                $action_btn = "<div class=\"col-sm-6 col-xl-4\">
                                    <div class=\"dropdown\">
                                        <button type=\"button\" class=\"btn btn-secondary dropdown-toggle\" id=\"dropdown-default-secondary\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Action
                                        </button>
                                        <div class=\"dropdown-menu font-size-sm\" aria-labelledby=\"dropdown-default-secondary\">";

                if($remarks == "pending") {
                    $edit_url = url('leaveApply/'.$id.'/edit');
                    $edit_btn = "<a class=\"dropdown-item\" href=\"$edit_url\">Edit</a>";
                    $action_btn .= "$edit_btn $toggle_delete_btn";
                } else {
                    $action_btn .= "$toggle_delete_btn";
                }

                $action_btn .= "</div>
                                    </div>
                                </div>";

                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $leave_type);
                array_push($temp, $start_date);
                array_push($temp, $end_date);
                array_push($temp, $total_leave);
                array_push($temp, $reason);
                array_push($temp, $remarks);
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
}
