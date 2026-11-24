<?php

namespace App\Mail;

use App\Models\PpdbPendaftar;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class PpdbDaftarUlangTokenMail extends Mailable
{
    public function __construct(
        public PpdbPendaftar $pendaftar,
        public string $actionUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tautan Daftar Ulang PPDB',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ppdb.daftar-ulang-token',
            with: [
                'pendaftar' => $this->pendaftar,
                'actionUrl' => $this->actionUrl,
            ],
        );
    }
}
