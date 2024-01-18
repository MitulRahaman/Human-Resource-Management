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

    public function setUpdateYear($updateYear)
    {
        $this->updateYear = $updateYear;
        return $this;
    }

    public function setTotalLeave($totalLeave)
    {
        $this->totalLeave = $totalLeave;
        return $this;
    }

    public function setName($name)
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
            $data->update(array('status' => Config::get('variable_constants.check.no')));
        else
            $data->update(array('status' => Config::get('variable_constants.check.yes')));
    }

    public function destroyLeave($id)
    {
        $data = LeaveType::find($id);
        $data->update(array('status' => 0));
        return $data->delete();
    }

    public function restoreLeave($id)
    {
        return DB::table('leave_types')->where('id', $id)->limit(1)->update(array('deleted_at' => NULL));
    }

    public function isNameExists()
    {
        return DB::table('leave_types')->where('name', '=', $this->name)->first();
    }

    public function isNameExistsForUpdate($current_name)
    {
        return DB::table('leave_types')->where('name', '!=', $current_name)->where('name', $this->name)->first();
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

    public function isTypeAndYearExist($data, $id)
    {
        return DB::table('total_yearly_leaves')->where('year', $data->updateYear)->where('leave_type_id', $id)->exists();
    }

    public function addTotalLeave($is_type_and_year_exist, $data, $id)
    {
        if($is_type_and_year_exist) {
            if($this->totalLeave != null) {
                return DB::table('total_yearly_leaves')->where('year',$this->updateYear)->where('leave_type_id', $id)->update(['total_leaves'=>$this->totalLeave]);
            }

        } else {
            if($this->totalLeave != null) {
                return TotalYearlyLeave::create([
                    'leave_type_id' => $id,
                    'year' => $this->updateYear,
                    'total_leaves'=> $this->totalLeave
                ]);
            }
        }
    }
}
