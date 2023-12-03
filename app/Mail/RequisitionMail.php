<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class RequisitionMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data, $assetType;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $assetType)
    {
        $this->data = $data;
        $this->assetType = $assetType;
    }
    public function build()
    {
        return $this->markdown('backend.pages.requisition.mailDetails');
    }
    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address(auth()->user()->email, auth()->user()->full_name),
            subject: 'Asset Requisition Request',
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
