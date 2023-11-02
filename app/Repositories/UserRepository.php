<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class UserRepository
{
    private $name;

    public function getTableData()
    {
        return DB::table('basic_info')->get();
    }

}