<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Invoice;

class InvoiceGenerated extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bukti Pembayaran SmartKost - #' . $this->invoice->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice_notif', // Pastikan mengarah ke file yang benar
        );
    }

    public function attachments(): array
    {
        return [];
    }
}