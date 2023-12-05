<?php

namespace App\Repositories;

use Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Traits\AuthorizationTrait;

class RequisitionRepository
{
    use AuthorizationTrait;
    private $id, $user_id, $name, $specification,$hasPermission, $asset_type_id, $status, $created_at, $updated_at, $deleted_at, $remarks;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function setUserId($user_id)
    {
        $this->user_id= $user_id;
        return $this;
    }
    public function setName($name)
    {
        $this->name=$name;
        return $this;
    }
    public function setSpecification($specification)
    {
        $this->specification=$specification;
        return $this;
    }
    public function setAssetTypeId($asset_type_id)
    {
        $this->asset_type_id=$asset_type_id;
        return $this;
    }
    public function setRemarks($remarks)
    {
        $this->remarks=$remarks;
        return $this;
    }
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function setDeletedAt($deleted_at)
    {
        $this->deleted_at = $deleted_at;
        return $this;
    }
    public function setPermission($hasPermission)
    {
        $this->hasPermission = $hasPermission;
        return $this;
    }
    public function getAllAssetType()
    {
        return DB::table('asset_types')->get();
    }
    public function getTableData()
    {
        return DB::table('requisition_requests as r')
            ->leftJoin('asset_types as at', function ($join) {
                $join->on('r.asset_type_id', '=', 'at.id');
            })
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->groupBy('r.id')
            ->select('r.*', 'at.id as asset_type_id', 'at.name as type_name', 'u.employee_id', 'u.full_name')
            ->when(!$this->hasPermission, function($query){
                $query->where('r.user_id', $this->user_id);
            })
            ->get();
    }
    public function create()
    {
        $userId = auth()->user()->id;
        return DB::table('requisition_requests')
            ->insertGetId([
                'user_id' => $userId,
                'name' => $this->name,
                'specification' => $this->specification,
                'asset_type_id' =>$this->asset_type_id,
                'remarks' => $this->remarks,
                'status' => $this->status,
                'created_at' => $this->created_at
            ]);
    }
    public function update()
    {
        return DB::table('requisition_requests')->where('id',$this->id)
            ->update([
                'name' => $this->name,
                'specification' => $this->specification,
                'asset_type_id' =>$this->asset_type_id,
                'remarks' => $this->remarks,
                'updated_at' => $this->updated_at
            ]);
    }
    public function getRequisitionRequest($id)
    {
        return DB::table('requisition_requests')->where('id','=',$id)->first();
    }
    public function delete($id)
    {
        return DB::table('requisition_requests')->where('id', '=',$id)->delete();
    }
    public function approve( $id)
    {
        return DB::table('requisition_requests')->where('id','=',$id)->update(['status'=> Config::get('variable_constants.status.approved')]);
    }
    public function reject( $id)
    {
        return DB::table('requisition_requests')->where('id','=',$id)->update(['status'=> Config::get('variable_constants.status.rejected')]);
    }
    public function cancel($id)
    {
        return DB::table('requisition_requests')->where('id','=',$id)->update(['status'=> Config::get('variable_constants.status.canceled')]);
    }
    public function getAssetTypeName($id)
    {
        $asset_type = '';
        if($id)
            $asset_type = DB::table('asset_types')->where('id',$id)
                ->whereNull('deleted_at')
                ->where('status','=',Config::get('variable_constants.activation.active'))
                ->first();
        return $asset_type;
    }
    public function getRequisitionEmailRecipient()
    {
        $appliedUser = DB::table('basic_info')->where('user_id', '=', $this->id)->first();
        if(!$appliedUser) return false;
        $lineManagerEmail = DB::table('users as u')
                                ->leftJoin('line_managers as lm', function ($join) {
                                    $join->on('u.id', '=', 'lm.user_id')
                                        ->whereNull('lm.deleted_at');
                                })
                                ->leftJoin('users as line_manager_user', 'line_manager_user.id', '=', 'lm.line_manager_user_id')
                                ->where('lm.user_id', '=', $appliedUser->user_id)
                                ->first();
        $recipientEmail = DB::table('permissions as p')
                                ->leftJoin('role_permissions as rp', 'p.id', '=', 'rp.permission_id')
                                ->leftJoin('basic_info as bi', 'bi.role_id', '=', 'rp.role_id')
                                ->where('p.slug', '=', 'manageLeaves')
                                ->where('bi.branch_id', '=', $appliedUser->branch_id)
                                ->first();
        if (!$recipientEmail || !$lineManagerEmail) {
            return false;
        }
        return [$lineManagerEmail, $recipientEmail];
    }
}
    
