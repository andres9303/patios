<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'filename',
        'filepath',
    ];

    public function attachmentable()
    {
        return $this->morphTo();
    }
}
