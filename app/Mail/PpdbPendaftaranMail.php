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
            subject: $this->emailSubject(),
        );
    }

    private function emailSubject(): string
    {
        return match ($this->pendaftar->status) {
            'verifikasi' => 'PPDB SDN Mekar Jaya - Sedang Diverifikasi',
            'diterima' => 'PPDB SDN Mekar Jaya - Pendaftaran Diterima',
            'ditolak' => 'PPDB SDN Mekar Jaya - Pendaftaran Ditolak',
            default => 'PPDB SDN Mekar Jaya - Pendaftaran Telah Berhasil',
        };
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ppdb.pendaftaran',
            with: [
                'pendaftar' => $this->pendaftar,
                'statusUrl' => $this->statusUrl,
                'subject' => $this->emailSubject(),
            ],
        );
    }
}