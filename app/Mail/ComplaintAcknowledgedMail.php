<?php

namespace App\Mail;

use Storage;
use App\Helpers\CommonHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class ComplaintAcknowledgedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title, $byWhom, $againstWhom, $description;
    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($data)
    {
        $this->againstWhom = $data['againstWhom'];
        $this->user_email = $data['email'];
    }

    public function build()
    {
        return $this->markdown('backend.pages.complaint.acknowledgedMailDetails');
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        $title = 'Complain acknowledgement Email';
        return new Envelope(
            from: new Address($this->user_email),
            subject:  $title
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
