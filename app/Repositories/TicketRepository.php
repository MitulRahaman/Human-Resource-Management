<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TicketRepository
{
    private $id, $created_by, $assigned_to, $priority, $deadline, $subject, $remarks,  $description,  $status, $created_at, $updated_at, $deleted_at;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function setCreatedBy($created_by)
    {
        $this->created_by = $created_by;
        return $this;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function setAssignedTo($assigned_to)
    {
        $this->assigned_to = $assigned_to;
        return $this;
    }
    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
        return $this;
    }
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;
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

    public function getUsers()
    {
        return DB::table('users')
            ->whereNull('deleted_at')
            ->where('status','=',Config::get('variable_constants.activation.active'))
            ->where('is_super_user', '=', Config::get('variable_constants.check.no'))
            ->get();
    }

    public function getUserEmail($id)
    {
        return (DB::table('users')
            ->whereNull('deleted_at')
            ->where('id','=', $id)
            ->select('email')
            ->first())->email;
    }

    public function create()
    {
        return DB::table('tickets')
            ->insert([
               'subject' => $this->subject,
               'created_by' => $this->created_by,
               'assigned_to' => $this->assigned_to,
               'priority' => $this->priority,
               'deadline' => $this->deadline,
               'description' => $this->description,
               'status' => $this->status,
               'created_at' => $this->created_at,
            ]);
    }

    public function update()
    {
        return DB::table('tickets')->where('id','=',$this->id)
            ->update([
                'subject' => $this->subject,
                'assigned_to' => $this->assigned_to,
                'priority' => $this->priority,
                'deadline' => $this->deadline,
                'description' => $this->description,
                'updated_at' => $this->updated_at,
            ]);
    }

    public function getTableData()
    {
        return DB::table('tickets as t')
            ->whereNull('t.deleted_at')
            ->leftJoin('users as cu', 'cu.id', '=', 't.created_by')
            ->leftJoin('users as au', 'au.id', '=', 't.assigned_to')
            ->select('t.id', 't.subject', 't.priority', 't.deadline','t.description', 't.remarks', 't.status', 't.created_by', 'cu.full_name as created_by_name', 't.assigned_to', 'au.full_name as assigned_to_name', DB::raw('date_format(t.created_at, "%d/%m/%Y") as created_at'))
            ->get();
    }

    public function getTicket()
    {
        return DB::table('tickets')->where('id','=',$this->id)->select('tickets.*',DB::raw('date_format(tickets.deadline, "%d-%m-%Y") as deadline'))->first();
    }

    public function close()
    {
        return DB::table('tickets')->where('id','=',$this->id)->update(['status'=> Config::get('variable_constants.ticket_status.closed')]);
    }

    public function hold()
    {
        return DB::table('tickets')->where('id','=',$this->id)->update(['status'=> Config::get('variable_constants.ticket_status.hold')]);
    }

    public function complete()
    {
        return DB::table('tickets')->where('id','=',$this->id)->update(['status'=> Config::get('variable_constants.ticket_status.completed'), 'remarks'=>$this->remarks]);
    }


}
