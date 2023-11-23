<?php

namespace App\Repositories;

use App\Models\BasicInfo;
use App\Models\Designation;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\LeaveType;
use App\Models\LeaveApply;
use App\Models\User;


class LeaveApplyRepository
{
    private $id;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getLeaveTypes()
    {
        return LeaveType::where('status', Config::get('variable_constants.activation.active'))->get();
    }
    public function getUserDesignation($id)
    {
        $user = User::where('id',$id)->select('is_super_user')->first();
        if($user->is_super_user)
            return "super_user";
        $designationId= BasicInfo::where('user_id',$id)->select('designation_id')->first();
        if(!$designationId)
            return false;
        $designation= Designation::where('id',$designationId->designation_id)->select('name')->first();
        return $designation->name;
    }
    public function getTableData()
    {
        $userId = auth()->user()->id;
        $userDesignation = $this->getUserDesignation($userId);
            return DB::table('leaves as l')
                ->leftJoin('leave_types as lt', function ($join) {
                    $join->on('l.leave_type_id', '=', 'lt.id');
                })
                ->leftJoin('users as u', 'l.user_id', '=', 'u.id')
                ->groupBy('l.id')
                ->select('l.*', 'lt.id as leave_type_id', 'lt.name', 'u.employee_id', 'u.full_name', 'u.phone_number')
                ->when($userDesignation=="HR", function($query )use ($userId){
                    $branchID= BasicInfo::where('user_id',$userId)->select('branch_id')->first();
                    $branchUsers = BasicInfo::where('branch_id', $branchID->branch_id)->pluck('user_id')->toArray();
                    $query->whereIn('l.user_id', $branchUsers);
                })
                ->when($userDesignation!="super_user" && $userDesignation!="HR", function($query)use ($userId){
                    $query->where('l.user_id', $userId);
                })
                ->get();
    }
    public function getLeaveAppliedEmailRecipent()
    {
        $branchIdForAppliedLeave = DB::table('basic_info')->where('user_id', '=', $this->id)->first()->branch_id; 
        $HR = DB::table('designations')->where('name', '=', 'HR')->first();
        if($HR == null ) {
            return false;
        }
        $HRIdForCurrentBranch = DB::table('branch_designations')->where('branch_id', '=', $branchIdForAppliedLeave)->where('designation_id', '=', $HR->id)->first();
        return DB::table('basic_info')->where('designation_id', '=', $HRIdForCurrentBranch->designation_id)->first()->preferred_email;
    } 

    public function storeLeaves($data)
    {
        $formattedStartDate = date("Y-m-d", strtotime($data->startDate));
        $formattedEndDate = date("Y-m-d", strtotime($data->endDate));

        $result = LeaveApply::create([
            'user_id' => auth()->user()->id,
            'leave_type_id' => $data->leaveTypeId,
            'start_date' => $formattedStartDate,
            'end_date' => $formattedEndDate,
            'total' => $data->totalLeave,
            'reason' => $data->reason,
            'status' => Config::get('variable_constants.leave_status.pending')
        ]);
        return $result;
    }

    public function getLeaveInfo()
    {
        return LeaveApply::find($this->id);
    }

    public function updateLeave($data)
    {
        if($data->startDate == null) {
            DB::table('leaves')
            ->where('id', '=', $this->id)
            ->update([
                'leave_type_id' => $data->leaveTypeId,
                'reason' => $data->reason,
            ]);
        } else {
            $formattedStartDate = date("Y-m-d", strtotime($data->startDate));
            $formattedEndDate = date("Y-m-d", strtotime($data->endDate));
            DB::table('leaves')
            ->where('id', '=', $this->id)
            ->update([
                'leave_type_id' => $data->leaveTypeId,
                'start_date' => $formattedStartDate,
                'end_date' => $formattedEndDate,
                'total' => $data->totalLeave,
                'reason' => $data->reason,
            ]);
        }
        return true;
    }
    public function approveLeave($data, $id)
    {
        return DB::table('leaves')->where('id',$id)->update(['status'=> Config::get('variable_constants.leave_status.approved'),
            'remarks'=>$data['remarks']]);
    }
    public function rejectLeave($data, $id)
    {
        return DB::table('leaves')->where('id',$id)->update(['status'=> Config::get('variable_constants.leave_status.rejected'),
            'remarks'=>$data['remarks']]);
    }
    public function cancelLeave($id)
    {
        return DB::table('leaves')->where('id',$id)->update(['status'=> Config::get('variable_constants.leave_status.canceled')]);
    }
    public function delete($id)
    {
        return DB::table('leaves')->where('id', $id)->delete();
    }
}
    
