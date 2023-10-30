<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\LeaveType;
use App\Models\TotalYearlyLeave;

class LeaveRepository
{
    private $name, $year;
    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }

    public function updateName($name)
    {
        $this->name = $name;
    }

    public function indexLeave()
    {
        return DB::table('total_yearly_leaves as tyl')
        ->rightjoin('leave_types as lt', 'lt.id', '=', 'tyl.leave_type_id')
        ->select('tyl.*', 'lt.name')
        ->get();
    }

    public function manageLeave()
    {
        return DB::table('leave_types')->get();
    }

    public function storeLeave($data)
    {
        $data = LeaveType::create([
            'name' => $data->name,
            'status' => 1,
        ]);

        if(TotalYearlyLeave::create(['leave_type_id' => $data->id]))
        {
            return true;
        }
    }

    public function editLeave($id)
    {
        return LeaveType::find($id);
    }

    public function updateLeave($data, $id)
    {
        $leave = LeaveType::find($id);
        return $leave->update($data->validated());
    }

    public function updateStatus($id)
    {
        $data = LeaveType::find($id);
                if($data->status)
                    $data->update(array('status' => 0));
                else
                    $data->update(array('status' => 1));
    }

    public function destroyLeave($id)
    {
        $data = LeaveType::find($id);
        $data->update(array('status' => 0));
        $data->delete();
    }

    public function restoreLeave($id)
    {
        DB::table('leave_types')->where('id', $id)->limit(1)->update(array('deleted_at' => NULL));
    }

    public function isNameExists()
    {
        if(DB::table('leave_types')->where('name', '=', $this->name)->first())
            return true;
        else
            return false;
    }

    public function isNameExistsForUpdate($current_name)
    {
        if(DB::table('leave_types')->where('name', '!=', $current_name)->where('name', $this->name)->first())
            return false;
        else
            return true;
    }

    public function getTypeWiseTotalLeavesData()
    {
        return DB::table('leave_types as lt')
        ->leftJoin('total_yearly_leaves as tyl', function ($join) {
            $join->on('lt.id', '=', 'tyl.leave_type_id');
            $join->where('tyl.year', '=', $this->year);
            $join->whereNull('tyl.deleted_at');
        })
        ->whereNull('lt.deleted_at')
        ->groupBy('lt.id')
        ->select('lt.id', 'lt.name', DB::raw('ifnull(tyl.total_leaves, 0) as total_leaves'))
        ->get();
    }
}