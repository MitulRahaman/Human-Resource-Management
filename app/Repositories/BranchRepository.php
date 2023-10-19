<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Branch;

class BranchRepository
{
    private $name;
    public function getAllBranchData()
    {
        return DB::table('branches as b')
            ->select('b.id', 'b.name', 'b.address', 'b.status', DB::raw('date_format(p.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(p.deleted_at, "%d/%m/%Y") as deleted_at'))
            ->get();
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function isNameExists()
    {
        if(DB::table('branches')->where('name', '=', $this->name)->first())
            return true;
        else
            return false;
    }
}