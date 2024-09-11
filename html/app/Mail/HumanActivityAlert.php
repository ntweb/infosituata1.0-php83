<?php

namespace App\Mail;

use App\Models\HumanActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class HumanActivityAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $humanactivity;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(HumanActivity $humanactivity)
    {
        $this->humanactivity = $humanactivity;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('dashboard.mail.humanactivity.alert');
    }
}
