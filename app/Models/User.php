<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, softDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'full_name',
        'nick_name',
        'email',
        'phone_number',
        'password',
        'image',
        'is_super_user',
        'is_registration_complete',
        'is_password_changed',
        'is_onboarding_complete',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function personalInfo()
    {
        return $this->hasOne(PersonalInfo::class, 'user_id', 'id');
    }

    public function academicInfo()
    {
        return $this->hasMany(AcademicInfo::class, 'user_id', 'id');
    }

    public function bankingInfo()
    {
        return $this->hasOne(BankingInfo::class, 'user_id', 'id');
    }

    public function emergencyContacts()
    {
        return $this->hasMany(EmergencyContact::class, 'user_id', 'id');
    }

    public function basicInfo()
    {
        return $this->hasOne(BasicInfo::class, 'user_id', 'id');
    }
}
