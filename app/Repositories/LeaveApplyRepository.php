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
        return DB::table('leaves')->get();
    }

    public function getLeaveTypeForTable($leaveTypeId)
    {
        return DB::table('leave_types')->where('id', '=', $leaveTypeId)->first()->name;
    }

    public function storeLeaves($data)
    {
        $formattedStartDate = date("Y-m-d", strtotime($data->startDate));
        $formattedEndDate = date("Y-m-d", strtotime($data->endDate));
        try {
            $create_user = LeaveApply::create([
                'user_id' => auth()->user()->id,
                'leave_type_id' => $data->leaveTypeId,
                'start_date' => $formattedStartDate,
                'end_date' => $formattedEndDate,
                'total' => $data->totalLeave,
                'reason' => $data->reason,
                'remarks' => "pending"
            ]);
            return true;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
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
    
