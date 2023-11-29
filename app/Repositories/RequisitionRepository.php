<?php

namespace App\Repositories;

use Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class RequisitionRepository
{
    private $id, $name, $specification, $asset_type_id, $status, $created_at, $updated_at, $deleted_at, $remarks;

    public function setId($id)
    {
        $this->id = $id;
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
    public function getAllAssetType()
    {
        return DB::table('asset_types')->get();
    }
    public function getTableData()
    {
        $userId = auth()->user()->id;
        $isHrSuperUser = true;
        return DB::table('requisition_requests as r')
            ->leftJoin('asset_types as at', function ($join) {
                $join->on('r.asset_type_id', '=', 'at.id');
            })
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->groupBy('r.id')
            ->select('r.*', 'at.id as asset_type_id', 'at.name as type_name', 'u.employee_id', 'u.full_name')
            ->when(!$isHrSuperUser, function($query)use ($userId){
                $query->where('r.user_id', $userId);
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
        return DB::table('requisition_requests')->where('id',$id)->first();
    }
    public function delete($id)
    {
        return DB::table('requisition_requests')->where('id', $id)->delete();
    }
    public function approve( $id)
    {
        return DB::table('requisition_requests')->where('id',$id)->update(['status'=> Config::get('variable_constants.status.approved')]);
    }
    public function reject( $id)
    {
        return DB::table('requisition_requests')->where('id',$id)->update(['status'=> Config::get('variable_constants.status.rejected')]);
    }
    public function cancel($id)
    {
        return DB::table('requisition_requests')->where('id',$id)->update(['status'=> Config::get('variable_constants.status.canceled')]);
    }
}
    
