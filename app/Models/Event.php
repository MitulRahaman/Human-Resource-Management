<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory, softDeletes;
    protected $fillable = [
        'title',
        'branch_id',
        'start_date',
        'end_date',
        'description',
        'itinerary'
    ];
}
