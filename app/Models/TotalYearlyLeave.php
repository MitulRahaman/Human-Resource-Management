<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TotalYearlyLeave extends Model
{
    use HasFactory, softDeletes;
    protected $fillable = ['leave_type_id','year','total_leaves'];
}
