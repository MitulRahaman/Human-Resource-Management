<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BasicInfo extends Model
{
    use HasFactory, softDeletes;

    protected $table = 'basic_info';
    protected $fillable = ['user_id', 'branch_id', 'department_id', 'designation_id', 'role_id', 'personal_email', 'preferred_email', 'joining_date', 'career_start_date',  'last_organization_id'];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id', 'id');
    }

    public function lastOrganization()
    {
        return $this->belongsTo(Organization::class, 'last_organization_id', 'id');
    }
}
