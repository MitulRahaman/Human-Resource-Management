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

  public function roleManagePermission()
  {
    $hasRoleManagePermission = DB::table('permissions as p')
      ->leftJoin('role_permissions as rp', 'p.id', '=', 'rp.permission_id')
      ->leftJoin('basic_info as bi', 'bi.role_id', '=', 'rp.role_id')
      ->where('p.slug', '=', 'manageRoles')
      ->where('bi.user_id', '=', $this->id)
      ->first();
    $isSuperUser = DB::table('users')->where('is_super_user', '=', 1)->where('id', '=', $this->id)->first();
    if($hasRoleManagePermission || $isSuperUser ) {
      return true;
    } else {
      return false;
    }
  }

  public function designationManagePermission()
  {
    $hasDesignationManagePermission = DB::table('permissions as p')
      ->leftJoin('role_permissions as rp', 'p.id', '=', 'rp.permission_id')
      ->leftJoin('basic_info as bi', 'bi.role_id', '=', 'rp.role_id')
      ->where('p.slug', '=', 'manageDesignations')
      ->where('bi.user_id', '=', $this->id)
      ->first();
    $isSuperUser = DB::table('users')->where('is_super_user', '=', 1)->where('id', '=', $this->id)->first();
    if($hasDesignationManagePermission || $isSuperUser ) {
      return true;
    } else {
      return false;
    }
  }

  public function bankManagePermission()
  {
    $hasBankManagePermission = DB::table('permissions as p')
      ->leftJoin('role_permissions as rp', 'p.id', '=', 'rp.permission_id')
      ->leftJoin('basic_info as bi', 'bi.role_id', '=', 'rp.role_id')
      ->where('p.slug', '=', 'manageBanks')
      ->where('bi.user_id', '=', $this->id)
      ->first();
    $isSuperUser = DB::table('users')->where('is_super_user', '=', 1)->where('id', '=', $this->id)->first();
    if($hasBankManagePermission || $isSuperUser ) {
      return true;
    } else {
      return false;
    }
  }

  public function degreeManagePermission()
  {
    $hasDegreeManagePermission = DB::table('permissions as p')
      ->leftJoin('role_permissions as rp', 'p.id', '=', 'rp.permission_id')
      ->leftJoin('basic_info as bi', 'bi.role_id', '=', 'rp.role_id')
      ->where('p.slug', '=', 'manageDegree')
      ->where('bi.user_id', '=', $this->id)
      ->first();
    $isSuperUser = DB::table('users')->where('is_super_user', '=', 1)->where('id', '=', $this->id)->first();
    if($hasDegreeManagePermission || $isSuperUser ) {
      return true;
    } else {
      return false;
    }
  }

  public function institutionManagePermission()
  {
    $hasInstitutionManagePermission = DB::table('permissions as p')
      ->leftJoin('role_permissions as rp', 'p.id', '=', 'rp.permission_id')
      ->leftJoin('basic_info as bi', 'bi.role_id', '=', 'rp.role_id')
      ->where('p.slug', '=', 'manageInstitution')
      ->where('bi.user_id', '=', $this->id)
      ->first();
    $isSuperUser = DB::table('users')->where('is_super_user', '=', 1)->where('id', '=', $this->id)->first();
    if($hasInstitutionManagePermission || $isSuperUser ) {
      return true;
    } else {
      return false;
    }
  }

  public function userManagePermission()
  {
    $hasUserManagePermission = DB::table('permissions as p')
      ->leftJoin('role_permissions as rp', 'p.id', '=', 'rp.permission_id')
      ->leftJoin('basic_info as bi', 'bi.role_id', '=', 'rp.role_id')
      ->where('p.slug', '=', 'manageUsers')
      ->where('bi.user_id', '=', $this->id)
      ->first();
    $isSuperUser = DB::table('users')->where('is_super_user', '=', 1)->where('id', '=', $this->id)->first();
    if($hasUserManagePermission || $isSuperUser ) {
      return true;
    } else {
      return false;
    }
  }


}
?>
  
