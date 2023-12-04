<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

trait AuthorizationTrait {

    private $id, $slug;

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

  public function hasPermission() : bool
  {
      if (Auth::user()->is_super_user) {
          return true;
      } else {
          $hasPermission = DB::table('role_permissions as rp')
              ->join('permissions as p', function ($join) {
                  $join->on('p.id', '=', 'rp.permission_id');
                  $join->where('p.slug', '=', $this->slug);
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

