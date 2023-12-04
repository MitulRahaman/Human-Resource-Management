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
use App\Traits\AuthorizationTrait;


class LeaveApplyRepository
{
    use AuthorizationTrait;
    private $id;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getLeaveTypes($id)
    {
        if($id == null) {
            return LeaveType::where('status', Config::get('variable_constants.activation.active'))->get();
        } else {
            return LeaveType::where('id', '=', $id)->first()->name;
        }

    }

    public function getTableData()
    {
        $userId = auth()->user()->id;

        $isHrSuperUser = $this->setId($userId)->setSlug('manageLeaves')->checkAuthorization();
        $usersUnderLineManager = DB::table('line_managers')->where('line_manager_user_id', '=', $userId)->whereNull('deleted_at')->pluck('user_id')->toArray();
        array_push($usersUnderLineManager, $userId);

        return DB::table('leaves as l')
            ->leftJoin('leave_types as lt', function ($join) {
                $join->on('l.leave_type_id', '=', 'lt.id');
            })
            ->leftJoin('users as u', 'l.user_id', '=', 'u.id')
            ->groupBy('l.id')
            ->select('l.*', 'lt.id as leave_type_id', 'lt.name', 'u.employee_id', 'u.full_name', 'u.phone_number')
            ->when(!$isHrSuperUser, function($query)use ($usersUnderLineManager){
                $query->whereIn('l.user_id', $usersUnderLineManager);
            })
            ->get();

    }

    public function getLeaveAppliedEmailRecipient()
    {
        $appliedUser = DB::table('basic_info')->where('user_id', '=', $this->id)->first();
        if($appliedUser == null ) {
            return false;
        }

        $getLineManagers  = DB::table('users as u')
        ->leftJoin('line_managers as lm', function ($join) {
            $join->on('u.id', '=', 'lm.user_id')
            ->whereNULL('lm.deleted_at');
        })
        ->where('lm.user_id', '=', $appliedUser->user_id)
        ->select('lm.line_manager_user_id')
        ->get()
        ->toArray();

        $lineManagerEmail = array();
        foreach ($getLineManagers as $glm) {
            array_push($lineManagerEmail, DB::table('users')->where('id', '=', $glm->line_manager_user_id)->first()->email);
        }

        $hasManageLeavePermission = DB::table('permissions as p')
            ->leftJoin('role_permissions as rp', 'p.id', '=', 'rp.permission_id')
            ->leftJoin('basic_info as bi', 'bi.role_id', '=', 'rp.role_id')
            ->where('p.slug', '=', 'manageLeaves')
            ->where('bi.branch_id', '=', $appliedUser->branch_id)
            ->select('rp.role_id')
            ->get()
            ->toArray();
        if($hasManageLeavePermission == null ) {
            return false;
        }

        $recipientEmail = array();
        foreach ($hasManageLeavePermission as $hmlp) {
            array_push($recipientEmail, DB::table('basic_info')->where('role_id', '=', $hmlp->role_id)->first()->preferred_email);
        }
        if($recipientEmail == null ) {
            return false;
        }
        return [$lineManagerEmail, $recipientEmail];
    }

    public function storeLeaves($data)
    {
        $data->startDate = date("Y-m-d", strtotime($data->startDate));
        $data->endDate = date("Y-m-d", strtotime($data->endDate));

        $result = LeaveApply::create([
            'user_id' => auth()->user()->id,
            'leave_type_id' => $data->leaveTypeId,
            'start_date' => $data->startDate,
            'end_date' => $data->endDate,
            'total' => $data->totalLeave,
            'reason' => $data->reason,
            'status' => Config::get('variable_constants.status.pending')
        ]);
        return $result;
    }

    public function getLeaveInfo()
    {
        return LeaveApply::find($this->id);
    }

    public function updateLeave($data)
    {
        $data->startDate = date("Y-m-d", strtotime(str_replace("/","-",$data->startDate)));
        $data->endDate = date("Y-m-d", strtotime(str_replace("/","-",$data->endDate)));
        if($data->totalLeave == null) {
            DB::table('leaves')
            ->where('id', '=', $this->id)
            ->update([
                'leave_type_id' => $data->leaveTypeId,
                'start_date' => $$data->startDatee,
                'end_date' => $data->endDate,
                'reason' => $data->reason,
            ]);
        } else {

            DB::table('leaves')
            ->where('id', '=', $this->id)
            ->update([
                'leave_type_id' => $data->leaveTypeId,
                'start_date' => $data->startDate,
                'end_date' => $data->endDate,
                'total' => $data->totalLeave,
                'reason' => $data->reason,
            ]);
        }
        return true;
    }
    public function recommendLeave($data, $id)
    {
        return DB::table('leaves')->where('id',$id)->update(['remarks'=>$data['recommend-modal-remarks']]);
    }
    public function approveLeave($data, $id)
    {
        return DB::table('leaves')->where('id',$id)->update(['status'=> Config::get('variable_constants.status.approved'),
            'remarks'=>$data['approve-modal-remarks']]);
    }
    public function rejectLeave($data, $id)
    {
        return DB::table('leaves')->where('id',$id)->update(['status'=> Config::get('variable_constants.status.rejected'),
            'remarks'=>$data['reject-modal-remarks']]);
    }
    public function cancelLeave($id)
    {
        return DB::table('leaves')->where('id',$id)->update(['status'=> Config::get('variable_constants.status.canceled')]);
    }
    public function delete($id)
    {
        return DB::table('leaves')->where('id', $id)->delete();
    }
}

