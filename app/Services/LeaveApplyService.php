<?php

namespace App\Services;

use Mail;
use App\Events\LeaveApplied;
use App\Mail\LeaveApplicationMail;
use App\Mail\LeaveApproveMail;
use App\Repositories\LeaveApplyRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Traits\AuthorizationTrait;

class LeaveApplyService
{
    use AuthorizationTrait;
    private $leaveApplyRepository;

    public function __construct(LeaveApplyRepository $leaveApplyRepository)
    {
        $this->leaveApplyRepository = $leaveApplyRepository;
    }
    public function getLeaveTypes()
    {
        return $this->leaveApplyRepository->getLeaveTypes($id = null);
    }
    public function storeLeaves($data)
    {
        if(is_object($this->leaveApplyRepository->storeLeaves($data))) {
            event(new LeaveApplied($data));
            return true;
        } else {
            return false;
        }
    }
    public function editLeave($id)
    {
        return $this->leaveApplyRepository->setId($id)->getLeaveInfo();
    }
    public function updateLeave($data, $id)
    {
        return $this->leaveApplyRepository->setId($id)->updateLeave($data);
    }
    public function LeaveApplicationEmail($data)
    {
        $leaveTypeName = null;
        $receivers = $this->leaveApplyRepository->setId(auth()->user()->id)->getLeaveAppliedEmailRecipient();
        if(!$receivers) {
            return false;
        }

        if($data->leaveTypeId) {
            $leaveTypeName = $this->leaveApplyRepository->getLeaveTypes($data->leaveTypeId);
            Mail::send((new LeaveApplicationMail($data, $leaveTypeName))->to($receivers[1])->cc($receivers[0]));
            return true;
        } else {
            Mail::send((new LeaveApproveMail($data))->to($receivers[1])->cc($receivers[0]));
            return true;
        }
    }
    public function recommendLeave($data, $id)
    {
        return $this->leaveApplyRepository->recommendLeave($data, $id);
    }
    public function approveLeave($data, $id)
    {
        if($this->leaveApplyRepository->approveLeave($data, $id)) {
            event(new LeaveApplied($data));
            return true;
        } else {
            return false;
        }
    }
    public function rejectLeave($data, $id)
    {
        return $this->leaveApplyRepository->rejectLeave($data, $id);
    }
    public function cancelLeave($id)
    {
        return $this->leaveApplyRepository->cancelLeave($id);
    }
    public function delete($id)
    {
        return $this->leaveApplyRepository->delete($id);
    }
    public function getTableData()
    {
        $result = $this->leaveApplyRepository->getTableData();
        $userId= auth()->user()->id;
        $hasManageLeavePermission = $this->setId($userId)->setSlug('manageLeaves')->hasPermission();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $id = $row->id;
                $employeeId = $row->employee_id;
                $employeeName = $row->full_name;
                $leave_type = $row->name;
                $start_date = date("d-m-Y", strtotime($row->start_date));
                $end_date = date("d-m-Y", strtotime($row->end_date));
                $total_leave = $row->total;
                $employeePhone= $row->phone_number;
                $reason = $row->reason;
                $remarks = $row->remarks;
                $status="";
                if($row->status== Config::get('variable_constants.status.pending'))
                    $status = "<span class=\"badge badge-primary\">pending</span><br>" ;
                elseif ($row->status== Config::get('variable_constants.status.approved'))
                    $status = "<span class=\"badge badge-success\">approved</span><br>" ;
                elseif ($row->status== Config::get('variable_constants.status.rejected'))
                    $status = "<span class=\"badge badge-danger\">rejected</span><br>" ;
                elseif ($row->status== Config::get('variable_constants.status.canceled'))
                    $status = "<span class=\"badge badge-danger\">canceled</span><br>" ;

                $delete_url = url('leaveApply/'.$id.'/delete');
                $toggle_delete_btn = "<a class=\"dropdown-item\" href=\"$delete_url\">Delete</a>";
                $action_btn = "<div class=\"col-sm-6 col-xl-4\">
                                    <div class=\"dropdown\">
                                        <button type=\"button\" class=\"btn btn-secondary dropdown-toggle\" id=\"dropdown-default-secondary\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Action
                                        </button>
                                        <div class=\"dropdown-menu font-size-sm\" aria-labelledby=\"dropdown-default-secondary\">";

                $recommend_btn="<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_recommend_modal(\"$id\", \"$leave_type\", \"$remarks\")'>Recommend</a>";
                $approve_btn="<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_approve_modal(\"$id\", \"$leave_type\", \"$remarks\", \"$start_date\", \"$end_date\")'>Approve</a>";
                $reject_btn="<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_reject_modal(\"$id\", \"$leave_type\", \"$remarks\")'>Reject</a>";
                if($hasManageLeavePermission)
                {
                    if($row->status== Config::get('variable_constants.status.pending'))
                    {
                        $action_btn .= "$approve_btn $reject_btn";
                    }
                }
                elseif ($userId==$row->user_id)
                {
                    if($row->status== Config::get('variable_constants.status.pending'))
                    {
                        $edit_url = url('leaveApply/'.$id.'/edit');
                        $edit_btn = "<a class=\"dropdown-item\" href=\"$edit_url\">Edit</a>";
                        $cancel_url = url('leaveApply/status/'.$id.'/cancel');
                        $cancel_btn = "<a class=\"dropdown-item\" href=\"$cancel_url\">Cancel</a>";
                        $action_btn .= "$edit_btn $cancel_btn $toggle_delete_btn";
                    }
                    else $action_btn .= "$toggle_delete_btn";
                } else
                {
                    if($row->status== Config::get('variable_constants.status.pending'))
                    {
                        $action_btn .= "$recommend_btn";
                    }
                }

                $action_btn .= "</div>
                                    </div>
                                </div>";

                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $employeeId);
                array_push($temp, $employeeName);
                array_push($temp, $employeePhone);
                array_push($temp, $leave_type);
                array_push($temp, $start_date);
                array_push($temp, $end_date);
                array_push($temp, $total_leave);
                array_push($temp, $reason);
                array_push($temp, $status);
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
