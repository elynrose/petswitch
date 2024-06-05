<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class ServiceRequestEmail extends Mailable implements ShouldQueue
{

    /**
     * The service request instance.
     *
     * @var \App\Models\ServiceRequest
     */
    public $serviceRequest;


    /**
     * Create a new message instance.
     *
     * @param  \App\Models\ServiceRequest  $serviceRequest
     * @return void
     */
    public function __construct(ServiceRequest $serviceRequest)
    {
        $this->serviceRequest = $serviceRequest;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.service_request')
                    ->subject('New Service Request')
                    ->with([
                        'serviceRequest' => $this->serviceRequest,
                    ]);
    }
}