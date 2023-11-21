<?php

namespace App\Repositories;

use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\LeaveType;
use App\Models\LeaveApply;

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

    public function getTableData()
    {    
        return DB::table('leaves as l')
        ->leftJoin('leave_types as lt', function ($join) {
            $join->on('l.leave_type_id', '=', 'lt.id');
        })
        ->where('l.user_id', auth()->user()->id)
        ->groupBy('l.id')
        ->select('l.*', 'lt.id as leave_type_id', 'lt.name')
        ->get();
    }

    public function getLeaveAppliedUser()
    {
        $branchIdForAppliedLeave = DB::table('basic_info')->where('user_id', '=', $this->id)->first()->branch_id; 
        $HRId = DB::table('designations')->where('name', '=', 'HR')->first()->id;
        $HRIdForCurrentBranch = DB::table('branch_designations')->where('branch_id', '=', $branchIdForAppliedLeave)->where('designation_id', '=', $HRId)->get()->toArray();

        return DB::table('basic_info')->where('designation_id', '=', $HRIdForCurrentBranch[0]->designation_id)->first()->preferred_email;
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
            'remarks' => "pending"
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
}
    
