<?php

namespace App\Mail;

use App\Models\PpdbPendaftar;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PpdbPendaftaranMail extends Mailable
{
    use Queueable, SerializesModels;

    public PpdbPendaftar $pendaftar;
    public ?string $statusUrl;

    public function __construct(
        PpdbPendaftar $pendaftar,
        ?string $statusUrl = null
    ) {
        $this->pendaftar = $pendaftar;
        $this->statusUrl = $statusUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Perbaikan Berkas Pendaftaran PPDB - SDN Mekar Jaya',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ppdb.pendaftaran',
            with: [
                'pendaftar' => $this->pendaftar,
                'statusUrl' => $this->statusUrl,
            ],
        );
    }
}