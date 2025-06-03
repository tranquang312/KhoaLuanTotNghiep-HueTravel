<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    public function show(Booking $booking)
    {
        return view('payment.show', compact('booking'));
    }

    public function checkout(Request $request, Booking $booking)
    {
        try {

            // Khởi tạo Stripe với API key
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            // Tạo checkout session
            $checkout_session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'vnd',
                        'product_data' => [
                            'name' => 'Thanh toán tour: ' . $booking->tour->name,
                            'description' => 'Ngày khởi hành: ' . $booking->start_date,
                        ],
                        'unit_amount' => intval($booking->total_price),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success', $booking) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancel', $booking),
                'metadata' => [
                    'booking_id' => $booking->id,
                ],
                'customer_email' => $booking->email,
            ]);

            return redirect($checkout_session->url);
        } catch (ApiErrorException $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi tạo phiên thanh toán: ' . $e->getMessage());
        }
    }

    public function success(Booking $booking)
    {
        try {
            // Lấy session_id từ URL
            $session_id = request()->get('session_id');
            //dd($session_id);
            if ($session_id) {
                // Khởi tạo Stripe
                Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

                // Lấy thông tin session từ Stripe
                $session = \Stripe\Checkout\Session::retrieve($session_id);

                // Cập nhật trạng thái thanh toán
                $booking->update([
                    'payment_status' => 'paid',
                    'payment_method' => 'stripe',
                    'transaction_id' => $session->payment_intent,
                ]);
            }

            return view('payment.success', compact('booking'));
        } catch (\Exception $e) {
            return redirect()->route('payment.show', $booking)
                ->with('error', 'Có lỗi xảy ra khi xác nhận thanh toán: ' . $e->getMessage());
        }
    }

    public function cancel(Booking $booking)
    {
        return view('payment.cancel', compact('booking'));
    }
    
}
