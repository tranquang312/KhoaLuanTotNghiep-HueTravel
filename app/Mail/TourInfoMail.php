<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TourInfoMail extends Mailable
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
        return $this->subject('Thông tin chuyến đi ' . $this->departure->tour->name)
                    ->view('emails.tour-info');
    }
} 