<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    private $userId, $password;

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

    public function getBasicInfo()
    {
        return DB::table('basic_info as bi')
            ->whereNull('bi.deleted_at')
            ->where('bi.user_id', '=', $this->userId)
            ->select('bi.branch_id', 'bi.department_id', 'bi.designation_id', 'bi.role_id')
            ->get()
            ->first();
    }
    public function changePassword()
    {
        $user = DB::table('users')
            ->where('id',auth()->user()->id)
            ->update([
                'password'=> Hash::make($this->password)
            ]);
        if($user==1) return true;
        return false;
    }
    public function getUserPassword()
    {
        $user = DB::table('users')->where('id', auth()->user()->id)->select('password')->first();
        return $user->password;
    }
}
