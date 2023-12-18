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

    public $user, $leaveType, $user_email, $user_name;
    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($data)
    {
        $this->user = $data['data'];
        $this->leaveType = $data['leaveTypeName'];
        $this->user_email = $data['user_email'];
        $this->user_name = $data['user_name'];
    }

    public function build()
    {
        $email = $this->markdown('backend.pages.leaveApply.mailDetails');
        if($this->user['files'] != null) {
            foreach($this->user['files'] as $photo) {
                $email->attach(storage_path('app/public/leaveAppliedFiles/'.$photo));
            }
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
        if($this->user['startDate'] == $this->user['endDate']) {
            $leaveMessage = $this->leaveType.' Application on '.$this->user['startDate'];
        } else {
            $leaveMessage = $this->leaveType.' Application from '.$this->user['startDate'].' to '.$this->user['endDate'];
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
