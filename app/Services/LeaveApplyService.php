<?php

namespace App\Services;

use Mail;
use App\Events\LeaveApplied;
use App\Jobs\LeaveApplyJob;
use App\Jobs\LeaveApproveJob;
use App\Repositories\LeaveApplyRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Traits\AuthorizationTrait;

class LeaveApplyService
{
    use AuthorizationTrait;
    private $leaveApplyRepository, $fileUploadService;

    public function __construct(LeaveApplyRepository $leaveApplyRepository, FileUploadService $fileUploadService)
    {
        $this->leaveApplyRepository = $leaveApplyRepository;
        $this->fileUploadService = $fileUploadService;
    }

    public function getLeaveTypes()
    {
        return $this->leaveApplyRepository->getLeaveTypes($id = null);
    }

    public function storeLeaves($request)
    {
        $fileName = null;
        if($request['photo']) {
            $fileName = $this->fileUploadService->setPath($request['photo']);
            $this->fileUploadService->setPathName(Config::get('variable_constants.file_path.leave'))->uploadFile($fileName, $request['photo']);
        }
        $data =[
            'leaveTypeId' => $request['leaveTypeId'],
            'startDate' =>  $request['startDate'],
            'endDate' => $request['endDate'],
            'reason'=> $request['reason'],
            'totalLeave' => $request['totalLeave'],
            'files' => $fileName
        ];

        if($this->leaveApplyRepository->storeLeaves($data)) {
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

    public function LeaveApplicationEmail($value)
    {
        if($value['leaveTypeId']) {
            $receivers = $this->leaveApplyRepository->setId(auth()->user()->id)->getLeaveAppliedEmailRecipient();
            if(!$receivers) {
                return false;
            }

            $leaveTypeName = $this->leaveApplyRepository->getLeaveTypes($value['leaveTypeId']);
            $data =[
                'data' => $value,
                'leaveTypeName' =>  $leaveTypeName,
                'to' => $receivers[1],
                'cc'=> $receivers[0],
                'user_email' => auth()->user()->email,
                'user_name' => auth()->user()->full_name,
            ];
            LeaveApplyJob::dispatch($data);
            return true;
        } else {
            $receivers = $this->leaveApplyRepository->getReciever($value->employeeId);
            $temp =[
                'leaveType' => $value->leaveType,
                'startDate' => $value->startDate,
                'endDate'=> $value->endDate,
            ];
            $data =[
                'data' => $temp,
                'to' => $receivers[1],
                'cc'=> $receivers[0],
                'user_email' => auth()->user()->email,
                'user_name' => auth()->user()->full_name,
            ];
            LeaveApproveJob::dispatch($data);
            return true;
        }
    }

    public function recommendLeave($data, $id)
    {
        return $this->leaveApplyRepository->recommendLeave($data, $id);
    }

    public function approveLeave($data, $id)
    {
        $lineManagers = $this->leaveApplyRepository->setId($data->employeeId)->getlineManagers();
        if($lineManagers) {
            $flag = 1;
            foreach($lineManagers as $lm) {
                if($lm->line_manager_user_id == auth()->user()->id) {
                    $flag = 0;
                    break;
                }
            }
            if($flag) {
                $isRecommended = $this->leaveApplyRepository->setId($id)->isRecommended();
                if(!$isRecommended) {
                    return false;
                }
            }
            if($this->leaveApplyRepository->approveLeave($data, $id)) {
                event(new LeaveApplied($data));
                return true;
            } else {
                return false;
            }
        } else {
            if($this->leaveApplyRepository->approveLeave($data, $id)) {
                event(new LeaveApplied($data));
                return true;
            } else {
                return false;
            }
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

    public function validFileSize($data)
    {
        if($data == null)
            return true;
        $totalSize = 0;
        foreach ($data as $d) {
            $totalSize +=  $d->getsize();
        }
        if($totalSize < 26214400 ) {
            return true;
        } else {
            return false;
        }
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
                if($row->status== Config::get('variable_constants.leave_status.pending'))
                    $status = "<span class=\"badge badge-primary\">pending</span><br>" ;
                elseif ($row->status== Config::get('variable_constants.leave_status.approved'))
                    $status = "<span class=\"badge badge-success\">approved</span><br>" ;
                elseif ($row->status== Config::get('variable_constants.leave_status.rejected'))
                    $status = "<span class=\"badge badge-danger\">rejected</span><br>" ;
                elseif ($row->status== Config::get('variable_constants.leave_status.canceled'))
                    $status = "<span class=\"badge badge-danger\">canceled</span><br>" ;

                $delete_url = url('leaveApply/'.$id.'/delete');
                $toggle_delete_btn = "<li><a class=\"dropdown-item\" href=\"$delete_url\">Delete</a></li>";
                $action_btn = "<div class=\"col-sm-6 col-xl-4\">
                                    <div class=\"dropdown\">
                                        <button type=\"button\" class=\"btn btn-secondary dropdown-toggle\" id=\"dropdown-default-secondary\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Action
                                        </button>
                                        <div class=\"dropdown-menu font-size-sm\" aria-labelledby=\"dropdown-default-secondary\">
                                        <ul style=\"max-height: 100px; overflow-x:hidden\">";

                $recommend_btn="<li><a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_recommend_modal(\"$id\", \"$leave_type\", \"$remarks\")'>Recommend</a></li>";
                $approve_btn="<li><a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_approve_modal(\"$id\", \"$leave_type\", \"$remarks\", \"$start_date\", \"$end_date\", \"$employeeId\")'>Approve</a></li>";
                $reject_btn="<li><a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_reject_modal(\"$id\", \"$leave_type\", \"$remarks\")'>Reject</a></li>";
                if($hasManageLeavePermission && ($userId == $row->user_id))
                {
                    if($row->status== Config::get('variable_constants.leave_status.pending'))
                    {
                        $edit_url = url('leaveApply/'.$id.'/edit');
                        $edit_btn = "<li><a class=\"dropdown-item\" href=\"$edit_url\">Edit</a></li>";
                        $cancel_url = url('leaveApply/status/'.$id.'/cancel');
                        $cancel_btn = "<li><a class=\"dropdown-item\" href=\"$cancel_url\">Cancel</a></li>";
                        $action_btn .= "$edit_btn $cancel_btn $toggle_delete_btn $approve_btn $reject_btn";
                    }
                    else
                    {
                        $action_btn = "N/A";
                    }

                }
                elseif ($hasManageLeavePermission && ($userId != $row->user_id))
                {
                    if($row->status== Config::get('variable_constants.leave_status.pending'))
                    {
                        $action_btn .= "$approve_btn $reject_btn";
                    }
                    else
                    {
                        $action_btn = "N/A";
                    }
                }
                elseif (!$hasManageLeavePermission && ($userId == $row->user_id))
                {
                    if($row->status== Config::get('variable_constants.leave_status.pending'))
                    {
                        $edit_url = url('leaveApply/'.$id.'/edit');
                        $edit_btn = "<li><a class=\"dropdown-item\" href=\"$edit_url\">Edit</a></li>";
                        $cancel_url = url('leaveApply/status/'.$id.'/cancel');
                        $cancel_btn = "<li><a class=\"dropdown-item\" href=\"$cancel_url\">Cancel</a></li>";
                        $action_btn .= "$edit_btn $cancel_btn $toggle_delete_btn";
                    }
                    else
                    {
                        $action_btn = "N/A";
                    }
                }
                else
                {
                    if($row->status== Config::get('variable_constants.leave_status.pending'))
                    {
                        $action_btn .= "$recommend_btn $reject_btn";
                    }
                    else
                    {
                        $action_btn = "N/A";
                    }
                }

                $action_btn .= "</ul>
                                </div>
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
                if($remarks == null) {
                    array_push($temp, "N/A");
                } else {
                    array_push($temp, $remarks);
                }
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

    public function getReportData()
    {
        $userInfo = $this->leaveApplyRepository->getReportData();
        $totaLeaves = $this->leaveApplyRepository->getTotalLeaves();
        $takenLeaves = $this->leaveApplyRepository->getTakenLeaves();
        $totalTakenLeavesPerUser = $this->leaveApplyRepository->getTotalTakenLeavesPerUser();
        $sum = 0;
        foreach($totaLeaves as $totaLeave) {
            $sum += $totaLeave->total_leaves;
        }

        if ($userInfo->count() > 0) {
            $data = array();
            foreach ($userInfo as $key=>$row) {
                $userId = $row->id;
                $employeeId = $row->employee_id;
                $employeeName = $row->user_name;
                $designation = $row->designation;
                if($row->joining_date) {
                    $joining_date = date("d-m-Y", strtotime($row->joining_date));
                } else {
                    $joining_date = "N/A";
                }
                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $employeeId);
                array_push($temp, $employeeName);
                if($designation) {
                    array_push($temp, $designation);
                } else {
                    array_push($temp, "N/A");
                }
                array_push($temp, $joining_date);
                for($i = 0; $i < count($totaLeaves); $i++) {
                    array_push($temp, $totaLeaves[$i]->total_leaves);
                    $flag = 1;
                    for($j = 0; $j < count($takenLeaves); $j++) {
                        if($takenLeaves[$j]->user_id == $userId && $totaLeaves[$i]->id == $takenLeaves[$j]->leave_type_id) {
                            array_push($temp, $takenLeaves[$j]->total);
                            $flag = 0;
                        }
                    }
                    if($flag) {
                        array_push($temp, 0);
                    }
                }
                array_push($temp, $sum);
                $flag = 1;
                for($i = 0; $i < count($totalTakenLeavesPerUser); $i++) {
                    if($userId == $totalTakenLeavesPerUser[$i]->user_id) {
                        array_push($temp, $totalTakenLeavesPerUser[$i]->total);
                        $flag = 0;
                    }
                }
                if($flag) {
                    array_push($temp, 0);
                }
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
