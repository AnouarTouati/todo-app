<?php

namespace App\Mail;

use App\Models\ToDo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ToDoReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $toDo;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ToDo $toDo)
    {
        $this->toDo = $toDo;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address('reminder@todoapp.com', 'Reminder bot'),
            subject: 'To Do Reminder',
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
            view: 'emails.to-do-reminder',
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
