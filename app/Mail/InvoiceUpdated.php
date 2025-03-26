<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $changes;
    public $subject;
    public $title;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice, array $changes, $subject = null, $title = null)
    {
        $this->invoice = $invoice;
        $this->changes = $changes;
        $this->subject($subject);
        $this->title = $title ?? ('Invoice Updated');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.invoice_updated')->with(['title' => $this->title]);
    }
}
