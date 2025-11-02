<?php

namespace App\Services;

use App\Models\ScheduleNotification;
use App\Models\ScheduleNotificationUsers;

class ScheduleService
{
    protected $sc_notification;
    protected $sc_notification_users;

    public function __construct(
        ScheduleNotification $notification,
        ScheduleNotificationUsers $notificationUsers
    )
    {
        $this->sc_notification = $notification;
        $this->sc_notification_users = $notificationUsers;
    }

    public function SaveScheduleNotification($data)
    {
        $scheduleNotification = $this->sc_notification->storeScheduleNotification($data);
        
        if($scheduleNotification && $data['target_type'] === 'selected')
        {
            $this->sc_notification_users->storeScheduleNotificationUsers($data['users'], $scheduleNotification->id);

            return response()->json([
                'status' => 'success',
                "message" => "Schedule Notification save successfully...."
            ], 200);

        }else{

            if($data['target_type'] === 'all')
            {
                return response()->json([
                    'status' => 'success',
                    "message" => "Schedule Notification save successfully...."
                ], 200);
            }else
            {
                return response()->json([
                    'status' => 'error',
                    "message" => "Schedule Notification not save successfully...."
                ], 400);
            }
        }   
    }

    public function fetchUserList()
    {
        return $this->sc_notification->with('scheduleNotificationUsers.users')
        ->get([
            'id',
            'type',
            'target_type',
            'title',
            'description',
            'status',
            'schedule_time',
            'created_at'
        ])->toArray();   
    }
}
?>