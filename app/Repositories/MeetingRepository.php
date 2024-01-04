<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MeetingRepository
{
    private  $name, $id, $url, $status, $created_at, $updated_at, $deleted_at;

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function setDeletedAt($deleted_at)
    {
        $this->deleted_at = $deleted_at;
        return $this;
    }

    //    =============================start meeting======================


    //    =============================end meeting======================

//    =============================start meeting places======================
    public function getAllMeetingPlaceData()
    {
        return DB::table('meeting_places')
            ->select('*',DB::raw('date_format(created_at, "%d/%m/%Y") as created_at'))
            ->get();
    }

    public function isNameExists()
    {
        return DB::table('meeting_places')->where('name', '=',$this->name)->exists();

    }

    public function isNameUnique()
    {
        return DB::table('meeting_places')->where('name', '=',$this->name)->where('id', '!=', $this->id)->first() ;
    }

    public function getMeetingPlace()
    {
        return DB::table('meeting_places')->where('id',$this->id)->select('*')->first();
    }

    public function createMeetingPlace()
    {
        return DB::table('meeting_places')
            ->insertGetId([
                'name' => $this->name,
                'status' => $this->status,
                'created_at' => $this->created_at
            ]);
    }

    public function updateMeetingPlace()
    {
        return DB::table('meeting_places')
            ->where('id', '=', $this->id)
            ->update([
                'name' => $this->name,
                'updated_at' => $this->updated_at
            ]);
    }

    public function changeMeetingPlaceStatus()
    {
        $meeting_place = DB::table('meeting_places')->where('id','=',$this->id)->first();
        $old_status = $meeting_place->status;
        $status = config('variable_constants.activation');
        $meeting_place->status = ($old_status == $status['active']) ? $status['inactive'] : $status['active'];
        return DB::table('meeting_places')
            ->where('id', $this->id)
            ->update(['status' => $meeting_place->status]);
    }

    public function deleteMeetingPlace()
    {
        return DB::table('meeting_places')->where('id','=',$this->id)->update(['deleted_at'=>$this->deleted_at]);
    }

    public function restoreMeetingPlace()
    {
        return DB::table('meeting_places')->where('id','=',$this->id)->update(['deleted_at'=>null]);
    }

    //    =============================end meeting places======================
}
