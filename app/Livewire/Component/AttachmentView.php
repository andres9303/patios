<?php

namespace App\Livewire\Component;

use Livewire\Component;

class AttachmentView extends Component
{
    public $attachmentableType;
    public $attachmentableId;
    public $attachments = [];

    public function mount($attachmentableType, $attachmentableId)
    {
        $this->attachmentableType = $attachmentableType;
        $this->attachmentableId = $attachmentableId;
        $this->loadAttachments();
    }
    
    public function loadAttachments()
    {
        $model = app($this->attachmentableType)::find($this->attachmentableId);
        $this->attachments = $model ? $model->attachments()->get() : [];
    }

    public function render()
    {
        return view('livewire.component.attachment-view');
    }
}
