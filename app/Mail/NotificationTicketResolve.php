<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationTicketResolve extends Mailable
{
    use Queueable, SerializesModels;

    public $numTicket;
    public $company;
    public $location;
    public $category;
    public $category2;
    public $text;
    public $type;
    public $resolve;
    
    public function __construct($data)
    {
        $this->numTicket = $data['numTicket'];
        $this->company = $data['company'];
        $this->location = $data['location'];
        $this->category = $data['category'];
        $this->category2 = $data['category2'];
        $this->text = $data['text'];
        $this->type = $data['type'];
        $this->resolve = $data['resolve'];
    }

    public function build()
    {
        return $this->from('notificaciones@mastic.com.co')
                    ->subject('Notificación Seguimiento de ticket')
                    ->view('emails.ticket-resolve')
                    ->with([
                        'numTicket' => $this->numTicket,
                        'company' => $this->company,
                        'location' => $this->location,
                        'category' => $this->category,
                        'category2' => $this->category2,
                        'text' => $this->text,
                        'type' => $this->type,
                        'resolve' => $this->resolve,
                    ]);
    }
}
