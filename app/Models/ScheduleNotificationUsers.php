<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleNotificationUsers extends Model
{
    protected $table = 'schedule_notification_users';
    
    protected $fillable = [
        'notification_id',
        'user_ID'
    ];

    public function storeScheduleNotificationUsers($data, $sc_Id)
    {
        return ScheduleNotificationUsers::insert(
            collect($data)->map(fn($id) => [
                'notification_id' => $sc_Id,
                'user_ID' => $id,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray()
        );
    }

    public function scheduleNotification()
    {
        return $this->belongsTo(ScheduleNotification::class, 'notification_id');    
    }

    public function users()
    {
        return $this->belongsTo(User::class,'user_ID')->select('id','name');   
    }
}
