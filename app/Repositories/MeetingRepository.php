<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MeetingRepository
{
    private  $name, $id, $title, $agenda, $date, $place, $start_time, $end_time, $description, $url, $status, $created_at, $updated_at, $deleted_at;

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

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setAgenda($agenda)
    {
        $this->agenda = $agenda;
        return $this;
    }

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function setPlace($place)
    {
        $this->place = $place;
        return $this;
    }

    public function setStartTime($start_time)
    {
        $this->start_time = $start_time;
        return $this;
    }

    public function setEndTime($end_time)
    {
        $this->end_time = $end_time;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
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
    public function getAllPlaces()
    {
        return DB::table('meeting_places')
            ->whereNull('deleted_at')
            ->where('status','=',Config::get('variable_constants.activation.active'))
            ->get();
    }

    public function getAllUsers()
    {
        return DB::table('users')
            ->whereNull('deleted_at')
            ->where('status','=',Config::get('variable_constants.activation.active'))
            ->where('is_super_user', '=', Config::get('variable_constants.check.no'))
            ->get();
    }

    public function getAllMeetingData()
    {
        return DB::table('meetings as m')
            ->leftJoin('meeting_places as mp', 'mp.id','m.place')
            ->select('m.*',DB::raw('date_format(m.date, "%d-%m-%Y") as created_at'),'mp.name as place',DB::raw('date_format(m.created_at, "%d-%m-%Y") as created_at'))
            ->get();
    }

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
