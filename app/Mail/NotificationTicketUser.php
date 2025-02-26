<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationTicketUser extends Mailable
{
    use Queueable, SerializesModels;

    public $numTicket;
    public $company;
    public $location;
    public $category;
    public $category2;
    public $name;
    public $text;
    
    public function __construct($data)
    {
        $this->numTicket = $data['numTicket'];
        $this->company = $data['company'];
        $this->location = $data['location'];
        $this->category = $data['category'];
        $this->category2 = $data['category2'];
        $this->name = $data['name'];
        $this->text = $data['text'];
    }

    public function build()
    {
        return $this->from('notificaciones@mastic.com.co')
                    ->subject('Notificación asignación de ticket')
                    ->view('emails.ticket-assigned')
                    ->with([
                        'numTicket' => $this->numTicket,
                        'company' => $this->company,
                        'location' => $this->location,
                        'category' => $this->category,
                        'category2' => $this->category2,
                        'name' => $this->name,
                        'text' => $this->text,
                    ]);
    }
}
