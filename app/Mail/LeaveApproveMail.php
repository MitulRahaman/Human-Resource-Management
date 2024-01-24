<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class LeaveApproveMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data, $user_email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data['data'];
        $this->user_email = $data['user_email'];
    }

    public function build()
    {
        return $this->markdown('backend.pages.leaveApply.approveMail');
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        if($this->data['startDate'] == $this->data['endDate']) {
            $leaveMessage = $this->data['leaveType'].' Approval on '.$this->data['startDate'];
        } else {
            $leaveMessage = $this->data['leaveType'].' Approval from '.$this->data['startDate'].' to '.$this->data['endDate'];
        }
        return new Envelope(
            from: new Address($this->user_email),
            subject: $leaveMessage
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
