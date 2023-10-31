<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Role;

class Permission extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
