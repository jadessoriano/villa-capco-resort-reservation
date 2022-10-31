<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationBooked extends Mailable
{
    use Queueable, SerializesModels;

    public string $messages = '';
    public string $link = '';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation)
    {
        $this->messages = 'Hi ' . ucfirst($reservation->user->first_name) . '! your reservation is booked. If your payment method is cash, bring the receipt as the proof of reservation. Please download it via the link provided. Thank you!';
        $this->link = asset('storage/' . Reservation::getReceiptFilepathFor($reservation->transaction_no));
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Reservation Booked',
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
            view: 'mail.reservation.booked',
            with: [
                'messages' => $this->messages,
                'link' => $this->link
            ]
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
