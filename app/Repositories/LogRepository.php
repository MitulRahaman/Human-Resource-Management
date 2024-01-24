<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;

class LogRepository
{

    public function getAllLogs()
    {
        $logs=DB::table('logs as l')
            ->join('users as u', 'u.id','=','l.user_id')
            ->select('u.employee_id', 'l.id','l.uri', 'l.params', 'l.method', DB::raw('date_format(l.created_at, "%d/%m/%Y") as created_at'))
            ->orderBy('l.id', 'desc')
            ->get();
        return $logs;
    }

}
