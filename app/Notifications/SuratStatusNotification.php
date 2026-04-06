<?php

namespace App\Notifications;

use App\Models\Surat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SuratStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Surat  $surat,
        public string $type,    // 'success' | 'warning' | 'danger' | 'info'
        public string $title,
        public string $message,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database']; // simpan di DB, tampil di navbar
    }

    public function toArray(object $notifiable): array
    {
        return [
            'surat_id' => $this->surat->id,
            'type'     => $this->type,
            'title'    => $this->title,
            'message'  => $this->message,
        ];
    }
}