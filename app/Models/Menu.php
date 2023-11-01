<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Permission;

class Menu extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'title',
        'url',
        'icon',
        'description',
        'menu_order',
        'parent_menu',
    ];
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
