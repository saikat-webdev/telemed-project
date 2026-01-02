<?php
namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;

class PaymentController extends \App\Http\Controllers\Controller
{
    public function pay(Request $request)
    {
        // dd($request->all());
        $id = $request->input('appointment_id');
        $amount = $request->input('amount');
        $appointment = Appointment::with('doctor')->findOrFail($id);

        // Amount: 500 INR = 50000 Paise
        //test amt
        // $amountInPaise = 1000 * 100;
        $amountInPaise = $amount * 100;
        return $request->user()->checkoutCharge($amountInPaise, "Consultation with Dr. {$appointment->doctor->name}", 1, [
            'success_url' => route('patient.appointments.success', $appointment->id) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('patient.appointments.index'),
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'inr',
                    'product_data' => [
                        'name' => "Consultation with Dr. {$appointment->doctor->name}",
                    ],
                    'unit_amount' => $amountInPaise, 
                ],
                'quantity' => 1,
            ]],
        ]);
    }

    public function success(Request $request, $id)
    {
        // 1. Get the appointment
        $appointment = Appointment::findOrFail($id);

        // 2. Use the Stripe SDK to retrieve the session details
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        
        // The {CHECKOUT_SESSION_ID} is a placeholder Stripe fills in the URL
        $session = $stripe->checkout->sessions->retrieve($request->get('session_id'));

        $newTransaction = Transaction::create([
            'appointment_id' => $appointment->id,
            'stripe_transaction_id' => $session->payment_intent,
            'amount' => $session->amount_total / 100,
            'currency' => 'inr',
            'status' => 'completed',
        ]);
        $transactionID = $newTransaction->id;
        
        $appointment->update([
            'status' => 2, // Fees Paid
            'transaction_id' => $transactionID,
        ]);

        return redirect()->route('patient.appointments.index')
                        ->with('success', 'Payment Successful! Ref ID: ' . $session->payment_intent);
    }
}