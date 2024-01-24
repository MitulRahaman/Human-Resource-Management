<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Role_Permission extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'role_id',
        'permission_id',

    ];
}
