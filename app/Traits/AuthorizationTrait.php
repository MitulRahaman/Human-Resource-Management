<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

trait AuthorizationTrait {

  public function setId($id)
  {
    $this->id = $id;
    return $this;
  }

  public function setSlug($slug)
  {
    $this->slug = $slug;
    return $this;
  }

  public function checkAuthorization()
  {
    $hasPermission = DB::table('permissions as p')
      ->leftJoin('role_permissions as rp', 'p.id', '=', 'rp.permission_id')
      ->leftJoin('basic_info as bi', 'bi.role_id', '=', 'rp.role_id')
      ->where('p.slug', '=', $this->slug)
      ->where('bi.user_id', '=', $this->id)
      ->first();
    if($hasPermission || auth()->user()->is_super_user ) {
      return true;
    } else {
      return false;
    }
  }

  public function hasPermission($permissionSlug) : bool
  {
      if (Auth::user()->is_super_user) {
          return true;
      } else {
          $hasPermission = DB::table('role_permissions as rp')
              ->join('permissions as p', function ($join) use ($permissionSlug) {
                  $join->on('p.id', '=', 'rp.permission_id');
                  $join->where('p.slug', '=', $permissionSlug);
                  $join->whereNull('p.deleted_at');
                  $join->where('p.status', '=', Config::get('variable_constants.activation.active'));
              })
              ->whereNull('rp.deleted_at')
              ->where('rp.status', '=', Config::get('variable_constants.activation.active'))
              ->where('rp.role_id', '=', session('user_data')['basic_info']->role_id)
              ->get()
              ->first();
          return (bool) $hasPermission;
      }
  }
}

