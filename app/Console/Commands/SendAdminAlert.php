<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\DailyReminderMail;
use Illuminate\Support\Facades\Mail;
use App\Models\WatchLog;
use App\Models\Participant;
use App\Models\User;
use App\Mail\UserAlert;
use App\Mail\AdminAlert;
use Illuminate\Support\Carbon;

class SendAdminAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-admin-alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $dayBeforeYesterday = Carbon::now()->subDays(2)->format('Y-m-d');
        
        $participants = Participant::all();
        
        foreach ($participants as $participant) {
            if($participant->status == 'in progress'){
                $whatchForYesterday = WatchLog::whereDate('watched_at', $yesterday)->where('participant_id', $participant->id)->exists();
                $whatchBeforeYesterday = WatchLog::whereDate('watched_at', $dayBeforeYesterday)->where('participant_id', $participant->id)->exists();
                $whatchforToday = WatchLog::whereDate('watched_at', $today)->where('participant_id', $participant->id)->exists();
                if(!$whatchforToday){
                    if(!$whatchForYesterday){
                        Mail::to($participant->user->email)->send(new UserAlert($participant->user));
                        if(!$whatchBeforeYesterday){
                            Mail::to(config('dental.adminEmail'))->send(new AdminAlert($participant));
                        }
                    }    
                }
            }
        }
    }
}
