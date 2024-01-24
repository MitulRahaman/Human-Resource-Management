<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveAttachments extends Model
{
    use HasFactory, softDeletes;
    protected $fillable = ['leave_id', 'attachment'];
    protected $table = 'leave_attachments';
}
