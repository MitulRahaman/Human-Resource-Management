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

class LeaveApplicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user, $leaveType;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $leaveType, $files)
    {
        $this->user = $user;
        $this->leaveType = $leaveType;
        $this->files = $files;
    }

    public function build()
    {
        $email = $this->markdown('backend.pages.leaveApply.mailDetails');
        foreach($this->files as $photo) {
            $email->attach(storage_path('app/public/leaveAppliedFiles/'.$photo));
        }
        return $email;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        if($this->user->startDate == $this->user->endDate) {
            $leaveMessage = $this->leaveType.' Application on '.$this->user->startDate;
        } else {
            $leaveMessage = $this->leaveType.' Application from '.$this->user->startDate.' to '.$this->user->endDate;
        }
        return new Envelope(
            from: new Address(auth()->user()->email, auth()->user()->full_name),
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
