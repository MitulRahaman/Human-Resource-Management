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
}
?>

