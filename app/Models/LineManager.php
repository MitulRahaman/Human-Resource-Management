<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class LineManager extends Model
{
    use HasFactory,softDeletes;
    protected $fillable = ['user_id', 'line_manager_user_id'];
}
