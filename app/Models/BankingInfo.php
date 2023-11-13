<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankingInfo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'banking_info';

    public function nominees()
    {
        return $this->hasMany(Nominee::class, 'banking_info_id', 'id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }
}
