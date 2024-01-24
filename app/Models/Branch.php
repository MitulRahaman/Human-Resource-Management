<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = ['name', 'address', 'status'];
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
