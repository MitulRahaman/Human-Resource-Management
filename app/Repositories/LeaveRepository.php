<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Leave;

class LeaveRepository
{
    private $name;
    public function getAllLeaveData()
    {
        return DB::table('leaves as l')
            ->select('l.id', 'l.name', 'l.status', DB::raw('date_format(l.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(l.deleted_at, "%d/%m/%Y") as deleted_at'))
            ->get();
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
        return DB::table('leaves')->get();
    }

    public function storeLeave($data)
    {
        return Leave::create([
            'name' => $data->name,
            'status' => 1,
        ]);
    }

    public function editLeave($id)
    {
        return Leave::find($id);
    }

    public function updateLeave($data, $id)
    {
        $leave = Leave::find($id);
        return $leave->update($data->validated());
    }

    public function updateStatus($id)
    {
        $data = Leave::find($id);
                if($data->status)
                    $data->update(array('status' => 0));
                else
                    $data->update(array('status' => 1));
    }

    public function destroyLeave($id)
    {
        $data = Leave::find($id);
        $data->update(array('status' => 0));
        $data->delete();
    }

    public function restoreLeave($id)
    {
        DB::table('leaves')->where('id', $id)->limit(1)->update(array('deleted_at' => NULL));
    }

    public function isNameExists()
    {
        if(DB::table('leaves')->where('name', '=', $this->name)->first())
            return true;
        else
            return false;
    }

    public function isNameExistsForUpdate($current_name)
    {
        if(DB::table('leaves')->where('name', '!=', $current_name)->where('name', $this->name)->first())
            return false;
        else
            return true;
    }
}