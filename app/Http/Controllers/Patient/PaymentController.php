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
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
        ]);

        $id = $request->input('appointment_id');
        $appointment = Appointment::with('doctor')
            ->where('patient_id', auth()->id())
            ->findOrFail($id);

        if ((int) $appointment->status !== 1) {
            return redirect()
                ->route('patient.appointments.index')
                ->withErrors(['payment' => 'Only confirmed appointments can be paid.']);
        }

        $amount = (float) ($appointment->doctor->fees ?? 0);

        if ($amount <= 0) {
            return redirect()
                ->route('patient.appointments.index')
                ->withErrors(['payment' => 'This doctor does not have a valid consultation fee configured.']);
        }

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
        $appointment = Appointment::where('patient_id', auth()->id())->findOrFail($id);

        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $session = $stripe->checkout->sessions->retrieve($request->get('session_id'));

        // Verify payment was successful
        if ($session->payment_status !== 'paid') {
            return redirect()->route('patient.appointments.index')
                            ->with('error', 'Payment was not successful. Please try again.');
        }

        $newTransaction = Transaction::create([
            'appointment_id' => $appointment->id,
            'stripe_transaction_id' => $session->payment_intent,
            'amount' => $session->amount_total / 100,
            'currency' => 'inr',
            'status' => 'completed',
        ]);
        $transactionID = $newTransaction->id;
        
        $appointment->update([
            'status' => 2,
            'transaction_id' => $transactionID,
        ]);

        return redirect()->route('patient.appointments.index')
                        ->with('success', 'Payment Successful! Ref ID: ' . $session->payment_intent);
    }
}
