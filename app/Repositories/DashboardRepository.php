<?php

namespace App\Repositories;

use Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Traits\AuthorizationTrait;

class DashboardRepository
{
    use AuthorizationTrait;
    private $offset, $limit;
    public function setOffset($offset)
    {
        $this->offset= $offset;
        return $this;
    }
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }
    public function getAllAssetType()
    {
        return DB::table('asset_types')->get();
    }
    public function getRequisitionTableData()
    {
        return DB::table('requisition_requests as r')
            ->leftJoin('asset_types as at', function ($join) {
                $join->on('r.asset_type_id', '=', 'at.id');
            })
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->groupBy('r.id')
            ->select('r.*', 'at.id as asset_type_id', 'at.name as type_name', 'u.employee_id', 'u.full_name')
            ->offset($this->offset)
            ->limit($this->limit)
            ->orderBy('r.id','desc')
            ->get();
    }
    public function totalRequisitionRequests()
    {
        return DB::table('requisition_requests')->count();
    }
    public function totalOnLeave()
    {
        return DB::table('leaves')
            ->whereDate('leaves.start_date', '<=', now())
            ->whereDate('leaves.end_date', '>=', now())
            ->count();
    }
    public function totalPendingLeave()
    {
        return DB::table('leaves')
            ->where('leaves.status','=', Config::get('variable_constants.leave_status.pending'))
            ->count();
    }
    public function totalPendingRequisition()
    {
        return DB::table('requisition_requests as r')
            ->where('r.status','=', Config::get('variable_constants.requisition_status.pending'))
            ->count();
    }
    public function totalUser()
    {
        return DB::table('users as u')
            ->where('u.status','=', Config::get('variable_constants.activation.active'))
            ->where('u.is_super_user','=', Config::get('variable_constants.check.no'))
            ->whereNull('deleted_at')
            ->count();
    }
    public function getOnLeaveTableData()
    {
        return DB::table('leaves')
            ->whereDate('leaves.start_date', '<=', now())
            ->whereDate('leaves.end_date', '>=', now())
            ->where('leaves.status','=', Config::get('variable_constants.leave_status.approved'))
            ->join('users', 'users.id', '=', 'leaves.user_id')
            ->join('basic_info', 'leaves.user_id', '=', 'basic_info.user_id')
            ->join('designations', 'basic_info.designation_id', '=', 'designations.id')
            ->select('leaves.user_id', 'users.employee_id', 'users.full_name', 'designations.name as designation_name')
            ->limit($this->limit)
            ->orderBy('leaves.id','desc')
            ->get();
    }
    public function getPendingLeaveTableData()
    {
        return DB::table('leaves')
            ->where('leaves.status','=', Config::get('variable_constants.leave_status.pending'))
            ->join('users', 'users.id', '=', 'leaves.user_id')
            ->join('leave_types', 'leave_types.id','=','leaves.leave_type_id')
            ->select('leave_types.name as leave_type', 'users.employee_id', 'users.full_name', 'leaves.start_date','leaves.end_date', 'leaves.created_at')
            ->limit($this->limit)
            ->orderBy('leaves.id','desc')
            ->get();
    }

}
    
