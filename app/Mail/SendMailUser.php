<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailUser extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $blade;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $subject, $blade, $data )
    {
        $this->subject = $subject;
        $this->blade = $blade;
        $this->data = $data;   
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->markdown($this->blade)->with($this->data);   
    }
}
