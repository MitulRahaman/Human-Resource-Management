<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveApply extends Model
{
    use HasFactory, softDeletes;
    protected $fillable = ['user_id', 'leave_type_id', 'start_date', 'end_date', 'total', 'reason', 'remarks'];
    protected $table = 'leaves';
}
