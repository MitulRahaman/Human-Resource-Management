<?php

namespace App\Repositories;

use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Complaint;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Traits\AuthorizationTrait;


class ComplaintRepository
{
    use AuthorizationTrait;
    private $id, $complaintId, $title, $description, $remarks, $file;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function setComplaintId($complaintId)
    {
        $this->complaintId = $complaintId;
        return $this;
    }
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;
        return $this;
    }
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    public function getAllUsers()
    {
        return User::where('is_super_user', '=', Config::get('variable_constants.check.no'))->where('id', '!=', auth()->user()->id)->get();
    }

    public function storeComplaints()
    {
        return Complaint::create([
            'title' => $this->title,
            'by_whom' => auth()->user()->id,
            'against_whom' => $this->id,
            'description' => $this->description,
            'complaint_date' => Carbon::now()->toDateTimeString(),
            'image' => $this->file,
        ]);
    }

    public function getTableData()
    {
        $userId = auth()->user()->id;
        $hasPermission = $this->setId($userId)->setSlug('manageComplaint')->hasPermission();
        return DB::table('complaints as c')
            ->leftJoin('users as u', function ($join) {
                $join->on('c.against_whom', '=', 'u.id');
            })
            ->leftJoin('users as _u', function ($join) {
                $join->on('c.by_whom', '=', '_u.id');
            })
            ->select('c.id', 'c.by_whom as user_id', 'c.title', 'c.description', 'c.complaint_date', 'c.status', 'c.remarks', '_u.full_name as byWhom', 'u.full_name as againstWhom')
            ->when(!$hasPermission, function($query)use ($userId){
                $query->where('c.by_whom', $userId);
            })
            ->get();
    }

    public function getComplaintInfo()
    {
        return Complaint::find($this->id);
    }

    public function updateComplaints()
    {
        return DB::table('complaints')
                ->where('id', '=', $this->complaintId)
                ->update([
                    'title' => $this->title,
                    'by_whom' => auth()->user()->id,
                    'against_whom' => $this->id,
                    'description' => $this->description,
                    'complaint_date' => Carbon::now()->toDateTimeString(),
                    'image' => $this->file,
            ]);
    }

    public function acknowledgeComplaint()
    {
        return DB::table('complaints')->where('id', '=', $this->complaintId)->update(['status'=> Config::get('variable_constants.leave_status.approved'),
            'remarks'=>$this->remarks['approve-modal-remarks']]);
    }

    public function rejectComplaint()
    {
        return DB::table('complaints')->where('id', '=', $this->complaintId)->update(['status'=> Config::get('variable_constants.leave_status.rejected'),
            'remarks'=>$this->remarks['reject-modal-remarks']]);
    }

    public function delete()
    {
        return DB::table('complaints')->where('id', '=', $this->complaintId)->delete();
    }


    public function getComplaintEmailRecipient()
    {
        if($this->complaintId) {
            return DB::table('complaints as c')
                ->leftJoin('users as u', 'u.id', '=', 'c.by_whom')
                ->leftJoin('users as _u', '_u.id', '=', 'c.against_whom')
                ->where('c.id', '=', $this->complaintId)
                ->select('u.email as email', '_u.full_name as name')
                ->first();
        }
        $appliedUser = DB::table('basic_info')->where('user_id', '=', auth()->user()->id)->first();
        if($appliedUser == null ) {
            return false;
        }

        $hasManageComplaintPermission = DB::table('permissions as p')
            ->leftJoin('role_permissions as rp', 'p.id', '=', 'rp.permission_id')
            ->leftJoin('basic_info as bi', 'bi.role_id', '=', 'rp.role_id')
            ->where('p.slug', '=', 'manageComplaint')
            ->where('bi.branch_id', '=', $appliedUser->branch_id)
            ->select('bi.preferred_email')
            ->get()
            ->toArray();
        if($hasManageComplaintPermission == null ) {
            return false;
        }

        $recipientEmail = array();
        foreach ($hasManageComplaintPermission as $hmlp) {
            array_push($recipientEmail, $hmlp->preferred_email);
        }
        if($recipientEmail == null ) {
            return false;
        }
        return $recipientEmail;
    }

    public function getAgainstWhomName()
    {
        return DB::table('users')->where('id', '=', $this->id)->first()->full_name;
    }
}

