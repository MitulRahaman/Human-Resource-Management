<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class Department extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = ['name', 'description', 'status'];

    public function branch_departments()
    {
        return $this->hasMany(BranchDepartment::class, 'department_id', 'id')->where('status', '=', Config::get('variable_constants.activation.active'));
    }
}
