<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BasicInfo extends Model
{
    use HasFactory, softDeletes;

    protected $table = 'basic_info';
    protected $fillable = ['user_id', 'branch_id', 'department_id', 'designation_id', 'personal_email', 'preferred_email', 'joining_date', 'career_start_date',  'last_organization_id'];

}