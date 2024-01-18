<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    private $userId, $password, $role_id, $permissions;

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function setRoleId($role_id)
    {
        $this->role_id = $role_id;
        return $this;
    }

    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
        return $this;
    }

    public function getMenus()
    {
        return DB::table('menu_permissions as mp')
            ->whereNull('mp.deleted_at')
            ->where('mp.status', '=', Config::get('variable_constants.activation.active'))
            ->whereIn('mp.permission_id',$this->permissions)
            ->leftJoin('menus as m', 'mp.menu_id', '=', 'm.id')
            ->where('m.status', '=', Config::get('variable_constants.activation.active'))
            ->whereNull('m.deleted_at')
            ->select('m.*')
            ->distinct('m.id')
            ->get();
    }

    public function getRolePermissions()
    {
        return DB::table('role_permissions')
            ->whereNull('deleted_at')
            ->where('status','=', Config::get('variable_constants.activation.active'))
            ->where('role_id', '=', $this->role_id)
            ->pluck('permission_id')
            ->toArray();
    }

    public function getAllPermission()
    {
        return DB::table('permissions')
            ->whereNull('deleted_at')
            ->where('status', '=', Config::get('variable_constants.activation.active'))
            ->pluck('id')
            ->toArray();
    }

    public function getBasicInfo()
    {
        if($this->userId == 1) {
            return DB::table('basic_info as bi')
                ->whereNull('bi.deleted_at')
                ->where('bi.user_id', '=', $this->userId)
                ->select('bi.branch_id', 'bi.department_id', 'bi.designation_id', 'bi.role_id')
                ->get()
                ->first();
        } else {
            return DB::table('basic_info as bi')
                ->leftJoin('designations as d', function ($join) {
                    $join->on('d.id', '=', 'bi.designation_id');
                })
                ->whereNull('bi.deleted_at')
                ->where('bi.user_id', '=', $this->userId)
                ->select('bi.branch_id', 'bi.department_id', 'bi.designation_id', 'bi.role_id', 'd.name')
                ->get()
                ->first();
        }
    }
    public function changePassword()
    {
        return DB::table('users')
            ->where('id','=',auth()->user()->id)
            ->update([
                'password'=> Hash::make($this->password),
                'is_password_changed' => Config::get('variable_constants.check.yes')
            ]);
    }
    public function getUserPassword()
    {
        $user = DB::table('users')->where('id', '=',auth()->user()->id)->select('password')->first();
        return $user->password;
    }
}
