<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\DailyReminderMail;
use Illuminate\Support\Facades\Mail;
use App\Models\WatchLog;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Support\Carbon;

class SendDailyReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-reminder';

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
        $participants = Participant::all();

        $today = Carbon::now()->format('Y-m-d');

        foreach ($participants as $participant) {
            $whatchforToday = WatchLog::whereDate('watched_at', $today)->where('participant_id', $participant->id)->exists();
            if (!$whatchforToday) {
                $participantCount = WatchLog::where('participant_id', $participant->id)->count();
                $participantRegisteringDate = $participant->created_at->format('Y-m-d');
                $dayLong = 0;
                if ($participant->status == 'finish watch' || $participant->status == 'completed') {
                    $dayLong = Carbon::parse($participant->finish_watch_at)->diffInDays(Carbon::parse($participantRegisteringDate)) - $participantCount + 1;
                } else {
                    $dayLong = Carbon::parse($today)->diffInDays(Carbon::parse($participantRegisteringDate)) - $participantCount + 1;
                }

                if ($participant->status == 'in progress' && $dayLong >= 0 && $dayLong < 10)
                    Mail::to($participant->user->email)->send(new DailyReminderMail($participant->user));
            }
        }

        // $user = User::find(60);
        // Mail::to('nourboubes@gmail.com')->send(new DailyReminderMail($user));

    }
}
