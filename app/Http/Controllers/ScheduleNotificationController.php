<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleNotificationRequest;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleNotificationController extends Controller
{
    protected $sc_notification;

    public function __construct(
        ScheduleService $schedule
    )
    {
        $this->sc_notification = $schedule;
    }

    public function store(ScheduleNotificationRequest $request)
    {
        $data = $request->all();

        return $this->sc_notification->SaveScheduleNotification($data);
    }

    public function fetch()
    {
        return response()->json([
            "status" => "success",
            "notifications_data" => $this->sc_notification->fetchUserList()
        ]);
    }
}
