<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class PpdbDaftarUlangCredentialsMail extends Mailable
{
    public function __construct(
        public string $studentName,
        public string $nis,
        public string $initialPassword,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Daftar Ulang Berhasil - Informasi Akun Siswa',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ppdb.daftar-ulang-credentials',
            with: [
                'studentName' => $this->studentName,
                'nis' => $this->nis,
                'initialPassword' => $this->initialPassword,
            ],
        );
    }
}