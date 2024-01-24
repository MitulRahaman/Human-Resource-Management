<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class WarningRepository
{
    private $id, $user_id, $hasPermission, $warning_by, $warning_to,  $date, $subject, $description,  $status, $created_at, $updated_at, $deleted_at;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function setWarningBy($warning_by)
    {
        $this->warning_by = $warning_by;
        return $this;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function setWarningTo($warning_to)
    {
        $this->warning_to = $warning_to;
        return $this;
    }

    public function setDate($date)
    {
        $this->date = $date;
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

    public function setUserId($user_id)
    {
        $this->user_id= $user_id;
        return $this;
    }

    public function setPermission($hasPermission)
    {
        $this->hasPermission = $hasPermission;
        return $this;
    }

    public function create()
    {
        return DB::table('warnings')
            ->insert([
                'subject' => $this->subject,
                'warning_by' => $this->warning_by,
                'warning_to' => $this->warning_to,
                'date' => $this->date,
                'description' => $this->description,
                'status' => $this->status,
                'created_at' => $this->created_at,
            ]);
    }

    public function update()
    {
        return DB::table('warnings')->where('id','=',$this->id)
            ->update([
                'subject' => $this->subject,
                'warning_by' => $this->warning_by,
                'warning_to' => $this->warning_to,
                'date' => $this->date,
                'description' => $this->description,
                'updated_at' => $this->updated_at,
            ]);
    }

    public function getUserEmail($id)
    {
        return (DB::table('users')
            ->whereNull('deleted_at')
            ->where('id','=', $id)
            ->select('email')
            ->first())->email;
    }

    public function acknowledged()
    {
        return DB::table('warnings')->where('id','=',$this->id)->update(['status'=> Config::get('variable_constants.warning_status.acknowledged')]);
    }

    public function getWarning()
    {
        return DB::table('warnings')->where('id','=',$this->id)->select('warnings.*',DB::raw('date_format(warnings.date, "%d-%m-%Y") as date'))->first();
    }

    public function getTableData()
    {
        return DB::table('warnings as w')
            ->whereNull('w.deleted_at')
            ->leftJoin('users as u', 'w.warning_to' ,'=', 'u.id')
            ->when(!$this->hasPermission, function($query){
                $query->where('w.warning_to', $this->user_id);
            })
            ->select('w.*', 'u.full_name as warning_to_name',DB::raw('date_format(w.created_at, "%d/%m/%Y") as created_at'))
            ->get();
    }

    public function getUsers()
    {
        return DB::table('users')
            ->whereNull('deleted_at')
            ->where('status','=',Config::get('variable_constants.activation.active'))
            ->where('is_super_user', '=', Config::get('variable_constants.check.no'))
            ->get();
    }

}
