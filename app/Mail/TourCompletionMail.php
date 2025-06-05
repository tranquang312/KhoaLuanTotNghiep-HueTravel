<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TourCompletionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $departure;

    public function __construct($booking, $departure)
    {
        $this->booking = $booking;
        $this->departure = $departure;
    }

    public function build()
    {
        return $this->subject('Cảm ơn bạn đã tham gia chuyến đi ' . $this->departure->tour->name)
                    ->view('emails.tour-completion');
    }
} 