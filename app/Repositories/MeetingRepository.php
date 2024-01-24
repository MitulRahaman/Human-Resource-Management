<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use function Ramsey\Collection\Map\keys;

class MeetingRepository
{
    private  $name, $notes, $id, $title, $meeting_minutes, $agenda, $participants, $date, $place, $start_time, $end_time, $description, $url, $status, $created_at, $updated_at, $deleted_at;

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

    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }

    public function setMeetingMinutes($meeting_minutes)
    {
        $this->meeting_minutes = $meeting_minutes;
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

    public function setParticipants($participants)
    {
        $this->participants = $participants;
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

    public function addNote()
    {
        return DB::table('meeting_participants')
            ->whereNull('deleted_at')
            ->where('id', '=', $this->id)
            ->update([
                'notes' => $this->notes,
            ]);
    }

    public function getNote()
    {
        return DB::table('meeting_participants')->whereNull('deleted_at')->where('id', '=', $this->id)->value('notes');
    }

    public function meetingMinute()
    {
        $meeting_minute = DB::table('meetings')->where('id','=', $this->id)->value('meeting_minutes');
        return json_decode($meeting_minute, true);
    }

    public function getUser($id)
    {
        return DB::table('users')->whereNull('deleted_at')
            ->where('status','=',Config::get('variable_constants.activation.active'))
            ->where('id','=',$id)
            ->first();
    }

    public function approveNote()
    {
        DB::beginTransaction();
        try {
            DB::table('meeting_participants')
                ->where('id', '=', $this->id)
                ->update(['note_status' => Config::get('variable_constants.note_status.approved')]);
            $values = DB::table('meeting_participants')->where('id', '=', $this->id)->first();
            $meeting_minute = DB::table('meetings')->where('id','=', $values->meeting_id)->value('meeting_minutes');
            if($meeting_minute)
                $notes = json_decode($meeting_minute, true);
            else
                $notes = [];
            $notes[$values->user_id] = $values->notes;
            DB::table('meetings')
                ->where('id','=', $values->meeting_id)
                ->update([
                    'meeting_minutes' => json_encode($notes),
                ]);
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }

    public function isMeetingParticipant($id)
    {
        return DB::table('meeting_participants')
            ->whereNull('deleted_at')
            ->where('meeting_id' , '=',$id)
            ->where('user_id', '=', auth()->user()->id)
            ->exists();
    }

    public function getAllMeetingData()
    {
        return DB::table('meetings as m')
            ->leftJoin('meeting_places as mp', 'mp.id','m.place')
            ->select('m.*',DB::raw('date_format(m.date, "%d-%m-%Y") as date'),'mp.name as place', DB::raw('date_format(m.created_at, "%d-%m-%Y") as created_at'),DB::raw('date_format(FROM_UNIXTIME(m.start_time / 1000), "%H:%i") as start_time_formatted'),DB::raw('date_format(FROM_UNIXTIME(m.end_time / 1000), "%H:%i") as end_time_formatted'))
            ->orderBy('m.id', 'desc')
            ->get();
    }

    public function getMeetingPlaceName($id)
    {
        return (DB::table('meeting_places')->where('id','=',$id)->select('name')->first())->name;
    }

    public function checkMeetingAvailability()
    {
        $end_time = $this->end_time + 1800000;
        return DB::table('meetings')->where('place','=',$this->place)
            ->where('date', '=', $this->date)
            ->where(function ($query) use ($end_time) {
                $query->whereBetween('start_time', [$this->start_time, $end_time])
                    ->orWhereBetween('end_time', [$this->start_time, $end_time]);
            })
            ->when($this->id, function($query){
                $query->where('id', '!=', $this->id);
            })
            ->exists();
    }

    public function createMeeting()
    {
        DB::beginTransaction();
        try {
            $meeting = DB::table('meetings')
                ->insertGetId([
                    'title' => $this->title,
                    'agenda' => $this->agenda,
                    'date' => $this->date,
                    'place' => $this->place,
                    'start_time' => $this->start_time,
                    'end_time' => $this->end_time,
                    'url' => $this->url,
                    'description' => $this->description,
                    'status' => $this->status,
                    'created_at' => $this->created_at,
                    'created_by' => auth()->user()->id
                ]);
            if($meeting)
            {
                if($this->participants)
                {
                    foreach ($this->participants as $p)
                    {
                        DB::table('meeting_participants')->insert([
                            'meeting_id'=> $meeting,
                            'user_id'=> $p,
                            'status' => Config::get('variable_constants.activation.active'),
                            'created_at' => $this->created_at,
                        ]);
                    }
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }

    public function getParticipantsEmails()
    {
        return DB::table('users')
            ->whereIn('id', $this->participants)
            ->pluck('email')
            ->toArray();
    }

    public function getMeeting()
    {
        $meeting = DB::table('meetings as m')
            ->where('m.id','=',$this->id)
            ->leftJoin('meeting_participants as mp', 'mp.meeting_id','=', 'm.id')
            ->select('m.*',DB::raw('date_format(m.date, "%d-%m-%Y") as date'),DB::raw('date_format(FROM_UNIXTIME(m.start_time / 1000), "%H:%i") as start_time_formatted'),DB::raw('date_format(FROM_UNIXTIME(m.end_time / 1000), "%H:%i") as end_time_formatted'))
            ->selectRaw('GROUP_CONCAT(mp.user_id) as participants')
            ->groupBy('m.id')
            ->first();
        $meeting->participants =($meeting->participants)? explode(',', $meeting->participants):[];
        return $meeting;
    }

    public function getMeetingParticipants()
    {
        return DB::table('meeting_participants as mp')
            ->where('mp.meeting_id', '=', $this->id)
            ->leftJoin('users as u', 'u.id', '=', 'mp.user_id')
            ->leftJoin('meetings as m', 'm.id', '=', 'mp.meeting_id')
            ->select('u.employee_id', 'u.full_name', 'mp.id', 'mp.notes', 'm.created_by', 'mp.user_id', 'mp.note_status')
            ->get();
    }

    public function updateMeeting()
    {
        DB::beginTransaction();
        try {
            $meeting = DB::table('meetings')
                ->where('id', '=',$this->id)
                ->update([
                    'title' => $this->title,
                    'agenda' => $this->agenda,
                    'date' => $this->date,
                    'place' => $this->place,
                    'start_time' => $this->start_time,
                    'end_time' => $this->end_time,
                    'url' => $this->url,
                    'description' => $this->description,
                    'updated_at' => $this->updated_at
                ]);
            if($meeting)
            {
                DB::table('meeting_participants')->where('meeting_id',$this->id)->delete();
                if($this->participants)
                {
                    foreach ($this->participants as $p)
                    {
                        DB::table('meeting_participants')->insert([
                            'meeting_id'=> $this->id,
                            'user_id'=> $p,
                            'status' => Config::get('variable_constants.activation.active'),
                            'updated_at' => $this->updated_at,
                        ]);
                    }
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }


    public function getPrevMeeting()
    {
        return DB::table('meetings as m')
            ->where('m.id','=',$this->id)
            ->select('m.*',DB::raw('date_format(FROM_UNIXTIME(start_time / 1000), "%H:%i") as start_time_formatted'),DB::raw('date_format(FROM_UNIXTIME(end_time / 1000), "%H:%i") as end_time_formatted'))
            ->first();
    }

    //    =============================end meeting======================

//    =============================start meeting places======================
    public function getAllMeetingPlaceData()
    {
        return DB::table('meeting_places')
            ->select('*',DB::raw('date_format(created_at, "%d/%m/%Y") as created_at'))
            ->orderBy('id', 'desc')
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
        return DB::table('meeting_places')->where('id', '=',$this->id)->select('*')->first();
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
