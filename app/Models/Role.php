<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'name',
        'description',
        'sl_no',
    ];
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
    public function branches()
    {
        return $this->belongsToMany(Branch::class);
    }
}
