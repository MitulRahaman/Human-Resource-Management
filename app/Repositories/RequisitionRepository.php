<?php

namespace App\Repositories;

use Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class RequisitionRepository
{
    private $id;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function getTableData()
    {
        $userId = auth()->user()->id;
        $isHrSuperUser = true;
        return DB::table('requisition_requests as r')
            ->leftJoin('asset_types as at', function ($join) {
                $join->on('r.asset_type_id', '=', 'at.id');
            })
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->groupBy('r.id')
            ->select('r.*', 'at.id as asset_type_id', 'at.name as type_name', 'u.employee_id', 'u.full_name')
            ->when(!$isHrSuperUser, function($query)use ($userId){
                $query->where('r.user_id', $userId);
            })
            ->get();
    }

}
    
