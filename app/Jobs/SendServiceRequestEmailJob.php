<?php
namespace App\Jobs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Mail\ServiceRequestEmail;
use Mail;



class SendServiceRequestEmailJob implements ShouldQueue
{

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // write the logic to send the service request email
        $email = new \App\Mail\ServiceRequestEmail();
        \Mail::to($user->email)->send($email);


    }
}