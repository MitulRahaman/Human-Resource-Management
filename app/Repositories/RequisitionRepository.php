<?php

namespace App\Repositories;

use Illuminate\Database\Console\DbCommand;
use Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Traits\AuthorizationTrait;

class RequisitionRepository
{
    use AuthorizationTrait;
    private $id, $user_id, $name, $specification,$hasPermission, $asset_type_id, $status, $created_at, $updated_at, $deleted_at, $remarks, $asset_id;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function setAssetId($asset_id)
    {
        $this->asset_id = $asset_id;
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
    public function isLineManager($id)
    {
        return DB::table('line_managers as l')
            ->where('l.line_manager_user_id', '=', $id)
            ->where('l.status', '=', Config::get('variable_constants.activation.active'))
            ->whereNull('l.deleted_at')
            ->exists();
    }
    public function getUsersUnderLineManager($id)
    {
        return DB::table('line_managers as l')
                ->where('l.line_manager_user_id', $id)
                ->where('l.status', '=', Config::get('variable_constants.activation.active'))
                ->whereNull('l.deleted_at')
                ->pluck('l.user_id')
                ->toArray();
    }
    public function getTableData()
    {
        $is_line_manager = $this->isLineManager(auth()->user()->id);
        $user_under_line_manager ='';
        if($is_line_manager)
        {
            $user_under_line_manager = $this->getUsersUnderLineManager(auth()->user()->id);
            array_push($user_under_line_manager, auth()->user()->id);
        }
        return DB::table('requisition_requests as r')
            ->leftJoin('asset_types as at', function ($join) {
                $join->on('r.asset_type_id', '=', 'at.id');
            })
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->select('r.*', 'at.id as asset_type_id', 'at.name as type_name', 'u.employee_id', 'u.full_name')
            ->when(!$this->hasPermission && !$is_line_manager, function($query){
                $query->where('r.user_id', $this->user_id);
            })
            ->when(!$this->hasPermission && $is_line_manager, function($query) use ($user_under_line_manager){
                $query->whereIn('r.user_id', $user_under_line_manager);
            })
            ->orderBy('r.id', 'desc')
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
    public function getRequisitionRequest()
    {
        return DB::table('requisition_requests')->where('id','=',$this->id)->first();
    }
    public function delete()
    {
        return DB::table('requisition_requests')->where('id', '=',$this->id)->delete();
    }
    public function approve()
    {
        return DB::table('requisition_requests')->where('id','=',$this->id)->update(['status'=> Config::get('variable_constants.requisition_status.approved')]);
    }
    public function reject()
    {
        return DB::table('requisition_requests')->where('id','=',$this->id)->update(['status'=> Config::get('variable_constants.requisition_status.rejected')]);
    }
    public function cancel()
    {
        return DB::table('requisition_requests')->where('id','=',$this->id)->update(['status'=> Config::get('variable_constants.requisition_status.canceled')]);
    }
    public function receive()
    {
        return DB::table('requisition_requests')->where('id','=',$this->id)->update(['status'=> Config::get('variable_constants.requisition_status.received')]);
    }
    public function processing()
    {
        return DB::table('requisition_requests')->where('id','=',$this->id)->update(['status'=> Config::get('variable_constants.requisition_status.processing')]);
    }
    public function deliver()
    {
        DB::beginTransaction();
        try {
             DB::table('requisition_requests')->where('id','=',$this->id)
                ->update(['status'=> Config::get('variable_constants.requisition_status.delivered'),
                    'asset_id'=>$this->asset_id]);

            $requested_user_id = (DB::table('requisition_requests')->where('id','=',$this->id)->select('user_id')->first())->user_id;
            $user_asset = DB::table('user_assets')->where('asset_id','=',$this->asset_id)->first();
            $branch_id = (DB::table('assets')->where('id','=',$this->asset_id)->select('branch_id')->first())->branch_id;
            if($user_asset)
            {
                DB::table('user_assets')->where('asset_id','=',$this->asset_id)->update([
                                    'status'=> Config::get('variable_constants.activation.inactive'),
                                    'updated_at'=>date('Y-m-d H:i:s')
                                ]);
                $asset = DB::table('user_assets')
                    ->insertGetId([
                        'user_id' => $requested_user_id,
                        'asset_id' => $this->asset_id,
                        'branch_id' => $branch_id,
                        'requisition_request_id' => $this->id,
                        'status' => Config::get('variable_constants.activation.active'),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
            }
            else{
                $asset = DB::table('user_assets')
                            ->insertGetId([
                                'user_id' => $requested_user_id,
                                'asset_id' => $this->asset_id,
                                'branch_id' => $branch_id,
                                'requisition_request_id' => $this->id,
                                'status' => Config::get('variable_constants.activation.active'),
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
            }

            if($asset)
            {
                $user= DB::table('users')->where('id','=',$requested_user_id)->first();
                if($user->is_asset_distributed==Config::get('variable_constants.check.no'))
                {
                    DB::table('users')->where('id','=',$requested_user_id)
                        ->update(['is_asset_distributed'=>Config::get('variable_constants.check.yes')]);
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
    public function getAllAssets()
    {
        $branch_id = DB::table('requisition_requests as rr')
            ->where('rr.id','=',$this->id)
            ->leftJoin('basic_info as bi', 'rr.user_id', '=', 'bi.user_id')
            ->select('bi.branch_id')
            ->first();
//        $super_user = auth()->user()->is_super_user;
        $super_user = (DB::table('requisition_requests as rr')
            ->where('rr.id','=',$this->id)
            ->leftJoin('users as u', 'u.id','=', 'rr.user_id')
            ->select('u.is_super_user')
            ->first())->is_super_user;
        return DB::table('assets as a')
            ->whereNull('a.deleted_at')
            ->where('a.status', '=', Config::get('variable_constants.activation.active'))
            ->where('a.condition', '=', Config::get('variable_constants.asset_condition.good'))
            ->leftJoin('user_assets as ua', function($join) {
                $join->on('ua.asset_id', '=', 'a.id')
                    ->where('ua.status', '=', Config::get('variable_constants.activation.active'));
            })
            ->leftJoin('users as u', 'u.id', '=', 'ua.user_id')
            ->when(!$super_user, function($query) use($branch_id){
                $query->where('a.branch_id', '=', $branch_id->branch_id);
            })
            ->select('a.*', 'u.full_name as user_name')
            ->get();
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
    public function getRequisitionInfo($id)
    {
        return DB::table('requisition_requests as r')
            ->where('r.id', '=', $id)
            ->leftJoin('asset_types as a', 'a.id',  '=', 'r.asset_type_id')
            ->leftJoin('users as u', 'u.id', '=', 'r.user_id')
            ->select('r.*','a.name as asset_type_name', 'u.employee_id', 'u.full_name')
            ->first();
    }
    public function getRequisitionEmailRecipient()
    {
        $appliedUser = DB::table('basic_info')->where('user_id', '=', $this->id)->first();
        if(!$appliedUser) return false;
        $getLineManagers  = DB::table('line_managers as lm')
                                    ->leftJoin('users as u', 'u.id', '=', 'lm.user_id')
                                    ->whereNULL('lm.deleted_at')
                                    ->where('lm.user_id', '=', $appliedUser->user_id)
                                    ->select('lm.line_manager_user_id')
                                    ->get()
                                    ->toArray();

        $lineManagerEmail = array();
        foreach ($getLineManagers as $lineManager) {
            array_push($lineManagerEmail, DB::table('users')->where('id', '=', $lineManager->line_manager_user_id)->first()->email);
        }
        $hasManageRequisitionPermission = DB::table('role_permissions as rp')
                                            ->leftJoin('permissions as p', 'p.id', '=', 'rp.permission_id')
                                            ->leftJoin('basic_info as bi', 'bi.role_id', '=', 'rp.role_id')
                                            ->where('p.slug', '=', 'notifyRequisitionRequest')
                                            ->where('bi.branch_id', '=', $appliedUser->branch_id)
                                            ->select('rp.role_id')
                                            ->get()
                                            ->toArray();
        $recipientEmail = array();
        foreach ($hasManageRequisitionPermission as $hasPermission) {
            array_push($recipientEmail, DB::table('basic_info')->where('role_id', '=', $hasPermission->role_id)->first()->preferred_email);
        }
        if(!$hasManageRequisitionPermission || !$recipientEmail) {
            return false;
        }
        return [$lineManagerEmail, $recipientEmail];
    }
    public function getRequestedUserMail($id)
    {
        return DB::table('requisition_requests as r')
            ->where('r.id','=', $id)
            ->leftJoin('basic_info as b', 'b.user_id', '=', 'r.user_id')
            ->select('b.preferred_email')
            ->first();
    }
    public function getRequisitionApproveEmailRecipient($id)
    {
        $appliedUser = DB::table('requisition_requests as r')
            ->where('r.id','=', $id)
            ->leftJoin('basic_info as b', 'b.user_id', '=', 'r.user_id')
            ->select('b.*')
            ->first();
        if(!$appliedUser) return false;
        $hasManageRequisitionPermission = DB::table('role_permissions as rp')
            ->leftJoin('permissions as p', 'p.id', '=', 'rp.permission_id')
            ->where('p.slug', '=', 'notifyRequisitionRequest')
            ->leftJoin('basic_info as bi', 'bi.role_id', '=', 'rp.role_id')
            ->where('bi.branch_id', '=', $appliedUser->branch_id)
            ->select('rp.role_id')
            ->get()
            ->toArray();
        $recipientEmail = array();
        $hasManageRequisitionPermission = collect($hasManageRequisitionPermission)->unique('role_id')->values()->all();
        foreach ($hasManageRequisitionPermission as $hasPermission) {
            $userBasicInfo = DB::table('basic_info')->where('role_id', '=', $hasPermission->role_id)
                                                        ->where('user_id', '!=', $this->id)
                                                        ->select('preferred_email')
                                                        ->get()->toArray();
            foreach ($userBasicInfo as $b)
            {
                array_push($recipientEmail,$b->preferred_email);
            }
        }
        if(!$hasManageRequisitionPermission || !$recipientEmail) {
            return false;
        }
        return $recipientEmail;
    }
}

