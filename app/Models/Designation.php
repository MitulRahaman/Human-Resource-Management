<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Designation extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'name',
        'description',
    ];
    public function branches()
    {
        return $this->belongsToMany(Branch::class);
    }
}
