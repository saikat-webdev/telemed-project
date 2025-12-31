<?php

namespace App\Http\Controllers\Patient;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends \App\Http\Controllers\Controller
{
    public function showDoctorMsg(Doctor $doctor)
    {
        // if(!$doctor->hasRole('doctor')) {
        //     abort(404);
        // }
        $patientId = auth()->id();
        $messages = Message::where(function($q) use ($patientId, $doctor) {
            $q->where('sender_id', $patientId)->where('receiver_id', $doctor->id);
        })->orWhere(function($q) use ($patientId, $doctor) {
            $q->where('sender_id', $doctor->id)->where('receiver_id', $patientId);
        })
        ->orderBy('created_at', 'asc')
        ->get();

        return view('patient.messages.chat', compact('doctor', 'messages'));
    }

    public function storeDoctorMsg(Request $request, User $doctor)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = new Message();
        $message->sender_id = auth()->id();
        $message->receiver_id = $doctor->id;
        $message->message = $request->input('message');
        $message->save();

        return redirect()->route('patients.messages.show', $doctor)->with('success', 'Message sent successfully.');
    }
}
