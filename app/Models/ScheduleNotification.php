<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ScheduleNotification extends Model
{
    protected $table = 'schedule_notifications';

    protected $fillable = [
        'type',
        'title',
        'description',
        'target_type',
        'schedule_time'
    ];

    protected $casts = [
        'type' => 'array'
    ];


    public function storeScheduleNotification($data)
    {
        return ScheduleNotification::create([
            'title' => $data['title'],
            'type' => $data['type'],
            'description' => $data['description'],
            'target_type' => $data['target_type'],
            'schedule_time' => $data['schedule_time']
        ]); 
    }

    public function scheduleTime(): Attribute
    {
        return Attribute::make(
            set : fn ($val) => date('Y-m-d H:i:s', strtotime($val)),
            get : fn ($val) => date('d-m-Y H:i:s', strtotime($val))
        );    
    }

    public function scheduleNotificationUsers()
    {
        return $this->hasMany(ScheduleNotificationUsers::class, 'notification_id')->select(['id','notification_id','user_ID']);    
    }

    public function createdAt(): Attribute
    {
        return Attribute::make(
            get : fn($value) => date('d-m-Y', strtotime($value))
        );    
    }
}
