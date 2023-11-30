<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class AuthRepository
{
    private $userId;

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function getBasicInfo()
    {
        return DB::table('basic_info as bi')
            ->whereNull('bi.deleted_at')
            ->where('bi.user_id', '=', $this->userId)
            ->select('bi.branch_id', 'bi.department_id', 'bi.designation_id', 'bi.role_id')
            ->get()
            ->first();    }
}
