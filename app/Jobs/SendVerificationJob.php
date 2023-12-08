<?php

namespace App\Jobs;

use App\Mail\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendVerificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user_email;
    protected $pin;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_email, $pin)
    {
        $this->user_email = $user_email;
        $this->pin = $pin;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mail = new VerifyEmail($this->pin);
        Mail::to($this->user_email)->send($mail);
    }
}
