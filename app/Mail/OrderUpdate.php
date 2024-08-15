<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $shippingCompany;
    public $trackingNumber;
    public $detailsOrder;
    /**
     * Create a new message instance.
     *
     * @return void
     */
     public function __construct($order, $shippingCompany, $trackingNumber,$detailsOrder)
    {
        $this->order = $order;
        $this->shippingCompany = $shippingCompany;
        $this->trackingNumber = $trackingNumber;
        $this->detailsOrder = $detailsOrder;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Order Update',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'emails.orders.enviar',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
