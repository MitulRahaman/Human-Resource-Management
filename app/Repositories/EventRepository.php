<?php

namespace App\Repositories;

use Validator;
use Carbon\Carbon;
use App\Models\Event;
use App\Helpers\CommonHelper;
use App\Traits\AuthorizationTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EventRepository
{
    use AuthorizationTrait;
    private $title, $eventId, $branchId, $departmentId, $participantId, $startDate, $endDate, $description, $file ;

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;
        return $this;
    }
    public function setBranchId($branchId)
    {
        $this->branchId = $branchId;
        return $this;
    }
    public function setDepartmentId($departmentId)
    {
        $this->departmentId = $departmentId;
        return $this;
    }
    public function setParticipantId($participantId)
    {
        $this->participantId = $participantId;
        return $this;
    }
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    public function getDepartments()
    {
        return DB::table('branch_departments')->where('branch_id', '=', $this->branchId)->get();
    }

    public function getParticipants()
    {
        return DB::table('basic_info')->where('department_id', '=', $this->departmentId)->get();
    }

    public function getDepartmentName()
    {
        $departmentName = array();
        foreach ($this->departmentId as $d) {
            $array = DB::table('departments')->select('name')->where('id', '=', $d)->first();
            array_push($departmentName, $array->name);
        }
        return [$this->departmentId, $departmentName, null, null];
    }

    public function getParticipantName()
    {
        $participantName = array();
        foreach ($this->participantId as $p) {
            $array = DB::table('users')->select('full_name')->where('id', '=', $p)->first();
            array_push($participantName, $array->full_name);
        }
        return [null, null, $this->participantId, $participantName];
    }

    public function storeEvents()
    {
        $this->startDate = CommonHelper::format_date($this->startDate, 'd/m/Y', 'Y-m-d');
        $this->endDate = CommonHelper::format_date($this->endDate, 'd/m/Y', 'Y-m-d');

        try {
            DB::beginTransaction();
            $event = Event::create([
                'title' => $this->title,
                'branch_id' => $this->branchId,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'description' => $this->description,
                'itinerary' => $this->file,
            ]);

            foreach ($this->departmentId as $departmentId) {
                DB::table('event_departments')
                    ->insert([
                        'department_id' => $departmentId,
                        'event_id' => $event->id,
                        'status' => Config::get('variable_constants.activation.active'),
                    ]);
            }

            foreach ($this->participantId as $participantId) {
                DB::table('event_participants')
                    ->insert([
                        'participant_id' => $participantId,
                        'event_id' => $event->id,
                        'status' => Config::get('variable_constants.activation.active'),
                    ]);
            }
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }

    public function getAllEvents()
    {
        if($this->eventId) {
            return DB::table('events')->where('id', '=', $this->eventId)->get();
        } else{
            return DB::table('events')->where('deleted_at', '=', null)->get();
        }

    }

    public function getCurrentBranch()
    {
        return DB::table('events as e')
            ->leftJoin('branches as b', function ($join) {
                $join->on('e.branch_id', '=', 'b.id');
            })
            ->where('e.id', '=', $this->eventId)
            ->first();
    }

    public function getCurrentDepartments()
    {
        return DB::table('events as e')
            ->leftJoin('event_departments as ed', function ($join) {
                $join->on('e.id', '=', 'ed.event_id');
            })
            ->leftJoin('departments as d', function ($join) {
                $join->on('d.id', '=', 'ed.department_id');
            })
            ->where('e.id', '=', $this->eventId)
            ->select('ed.department_id', 'd.name')
            ->get();
    }

    public function getAvailableDepartments()
    {
        return DB::table('events as e')
            ->leftJoin('branch_departments as bd', function ($join) {
                $join->on('e.branch_id', '=', 'bd.branch_id');
            })
            ->leftJoin('departments as d', function ($join) {
                $join->on('d.id', '=', 'bd.department_id');
            })
            ->where('e.id', '=', $this->eventId)
            ->select('bd.department_id', 'd.name')
            ->get();

    }

    public function getCurrentUsers()
    {
        return DB::table('events as e')
            ->leftJoin('event_participants as ep', function ($join) {
                $join->on('e.id', '=', 'ep.event_id');
            })
            ->leftJoin('users as u', function ($join) {
                $join->on('u.id', '=', 'ep.participant_id');
            })
            ->where('e.id', '=', $this->eventId)
            ->select('ep.participant_id', 'u.full_name')
            ->get();
    }

    public function getAvailableUsers($currentDepartments)
    {
        $temp = array();
        foreach($currentDepartments as $currentDepartment) {
            array_push($temp, $currentDepartment->department_id);
        }
        return DB::table('basic_info as bi')
            ->leftJoin('users as u', function ($join) {
                $join->on('u.id', '=', 'bi.user_id');
            })
            ->whereIn('bi.department_id', $temp)
            ->select('bi.user_id as participant_id', 'u.full_name')
            ->get();
    }

    public function updateEvents()
    {
        $this->startDate = CommonHelper::format_date($this->startDate, 'd/m/Y', 'Y-m-d');
        $this->endDate = CommonHelper::format_date($this->endDate, 'd/m/Y', 'Y-m-d');

        try {
            DB::beginTransaction();
            DB::table('events')
            ->where('id', '=', $this->eventId)
            ->update([
                'title' => $this->title,
                'branch_id' => $this->branchId,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'description' => $this->description,
                'itinerary' => $this->file,
            ]);

            DB::table('event_departments')->where('event_id', '=', $this->eventId)->delete();
            foreach ($this->departmentId as $departmentId) {
                DB::table('event_departments')
                    ->insert([
                        'department_id' => $departmentId,
                        'event_id' => $this->eventId,
                        'status' => Config::get('variable_constants.activation.active'),
                    ]);
            }

            DB::table('event_participants')->where('event_id', '=', $this->eventId)->delete();
            foreach ($this->participantId as $participantId) {
                DB::table('event_participants')
                    ->insert([
                        'participant_id' => $participantId,
                        'event_id' => $this->eventId,
                        'status' => Config::get('variable_constants.activation.active'),
                    ]);
            }
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }

    public function destroyEvent()
    {
        $data = Event::find($this->eventId);
        return $data->delete();
    }
}

