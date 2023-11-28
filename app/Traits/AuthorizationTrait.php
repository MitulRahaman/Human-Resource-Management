<?php

namespace App\Traits;

use App\Services\AuthorizationService;
use Illuminate\Support\Facades\DB;

trait AuthorizationTrait {

  public function setId($id)
  {
    $this->id = $id;
    return $this;
  }

  public function manageLeaveAuthorization()
  {
    $hasManageLeavePermission = DB::table('permissions as p')
      ->leftJoin('role_permissions as rp', 'p.id', '=', 'rp.permission_id')
      ->leftJoin('basic_info as bi', 'bi.role_id', '=', 'rp.role_id')
      ->where('p.slug', '=', 'manageLeaves')
      ->where('bi.user_id', '=', $this->id)
      ->first();
    $isSuperUser = DB::table('users')->where('is_super_user', '=', 1)->where('id', '=', $this->id)->first();
    if($hasManageLeavePermission || $isSuperUser ) {
      return true;
    } else {
      return false;
    }
  }

  public function branchManagePermission()
  {
    $hasBranchManagePermission = DB::table('permissions as p')
      ->leftJoin('role_permissions as rp', 'p.id', '=', 'rp.permission_id')
      ->leftJoin('basic_info as bi', 'bi.role_id', '=', 'rp.role_id')
      ->where('p.slug', '=', 'manageBranch')
      ->where('bi.user_id', '=', $this->id)
      ->first();
    $isSuperUser = DB::table('users')->where('is_super_user', '=', 1)->where('id', '=', $this->id)->first();
    if($hasBranchManagePermission || $isSuperUser ) {
      return true;
    } else {
      return false;
    }
  }

  public function departmentManagePermission()
  {
    $hasDepartmentManagePermission = DB::table('permissions as p')
      ->leftJoin('role_permissions as rp', 'p.id', '=', 'rp.permission_id')
      ->leftJoin('basic_info as bi', 'bi.role_id', '=', 'rp.role_id')
      ->where('p.slug', '=', 'manageDepartments')
      ->where('bi.user_id', '=', $this->id)
      ->first();
    $isSuperUser = DB::table('users')->where('is_super_user', '=', 1)->where('id', '=', $this->id)->first();
    if($hasDepartmentManagePermission || $isSuperUser ) {
      return true;
    } else {
      return false;
    }
  }


}
?>
  
