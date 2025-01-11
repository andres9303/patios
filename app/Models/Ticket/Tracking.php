<?php

namespace App\Models\Ticket;

use App\Models\Attachment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'text',
        'date',
        'state',
        'type',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }
}
