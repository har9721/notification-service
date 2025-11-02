<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'alert_via',
        'role_id',
        'is_active',
        'is_otp_based_login'
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function password()
    {
        return Attribute::make(
            set: fn ($value) => Hash::make($value)
        );
    }

    public function alertVia(): Attribute{
        return Attribute::make(
            set : fn ($value) => implode(',', $value),
        );
    }

    public function verifyUser($mobile)
    {
        return $this->where('mobile', $mobile)->whereNull('deleted_at')->first();
    }

    public function generateLoginToken(User $user)
    {
        return $user->createToken('auth_token')->plainTextToken;
    }

    public function getUserList()
    {
        return User::with('roles')
            ->whereNot('role_id', 1)
            ->whereNull('deleted_at')->select(['id', 'name', 'role_id', 'mobile', 'alert_via', 'created_at'])
            ->paginate(10);   
    }

    public function createdAt(): Attribute
    {
        return Attribute::make(
            get : fn($value) => date('d-m-Y', strtotime($value))
        );    
    }

    public function notificationUsers()
    {
        return $this->hasOne(ScheduleNotificationUsers::class, 'user_ID')->select(['id','notification_id','user_ID']);    
    }

    public function roles()
    {
        return $this->belongsTo(Mst_Roles::class, 'role_id')->select('id','name');
    }
}
