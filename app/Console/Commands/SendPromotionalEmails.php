<?php

namespace App\Console\Commands;

use App\Jobs\SendPromotionNotification;
use App\Models\ScheduleNotification;
use App\Notifications\PromotionalNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

class SendPromotionalEmails extends Command
{
    protected $signature = 'send-promotional-emails';

    protected $description = 'This command is used to send promotional emails!';

    public function handle()
    {
        info('------------------Inside SendPromotionalEmails Command-------------------');
        $emailUserList = fetchUserList(['email']);
        info($emailUserList);
        $smsUserList = fetchUserList(['sms']);
        $current_time = date('Y-m-d h:i:s', strtotime(now()."+5 hours 30 minutes"));
        info('Current Time: '.$current_time);

        $schedule_notification = ScheduleNotification::where('status', 'pending')
            ->where('schedule_time', '>=' , $current_time)
            ->select('id','title', 'type', 'description','target_type')
            ->get()
            ->each(function ($notification) use($emailUserList, $smsUserList){

                $notification_data = [
                    'subject' => $notification->title,
                    'desc' => $notification->description
                ];

                if(in_array("email", $notification->type))
                {
                    $emailUserList->chunk(3)->each(function ($checkedUsers) use($notification_data){
                        info($checkedUsers);    
                        info('----------------------------------------');
                        $jobs = $checkedUsers->map(
                                fn($u) => (new SendPromotionNotification($u, $notification_data
                                    ))
                                    // ->onQueue('low_priority_queue')
                                    // ->delay(now()->addSeconds(60))
                            );

                        Bus::batch($jobs)
                            ->progress(function ($batch) {
                                info("Batch ID: {$batch->id} is processing.");
                            })
                            ->then(function ($batch) {
                                info("Batch ID: {$batch->id} has completed.");
                            })
                            ->finally(function ($batch) {
                                info("Batch ID: {$batch->id} has finished.");
                                sleep(60);
                            })
                            ->onQueue('low_priority_queue')
                            ->dispatch();
                    });
                }

                if(in_array("sms", $notification->type))
                {

                }

                $notification->status = 'sent';
                $notification->save();
            });
    }
}
