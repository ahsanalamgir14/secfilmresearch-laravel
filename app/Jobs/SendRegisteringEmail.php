<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\ParticipantRegistered;
use App\Mail\WellcomeEmail;

class SendRegisteringEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        // Mail::to($this->user->email)->send(new ParticipantRegistered($this->user->name));
        Mail::to(config('dental.adminEmail'))->send(new ParticipantRegistered($this->user));
        Mail::to($this->user->email)->send(new WellcomeEmail($this->user));

    }
}
