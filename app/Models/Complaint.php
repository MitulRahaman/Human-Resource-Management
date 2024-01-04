<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Complaint extends Model
{
    use HasFactory, softDeletes;
    protected $fillable = [
        'title',
        'by_whom',
        'against_whom',
        'description',
        'complaint_date',
        'image',
        'status',
        'remarks'
    ];
}
