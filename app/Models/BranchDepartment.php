<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchDepartment extends Model
{
    use HasFactory, softDeletes;

    //protected $fillable = ['department_id', 'branch_id', 'status'];
}
