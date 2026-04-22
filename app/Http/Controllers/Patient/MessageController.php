<?php

namespace App\Http\Controllers\Patient;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $patientId = auth()->id();
        $doctorIds = Appointment::where('patient_id', $patientId)
            ->pluck('doctor_id')
            ->unique()
            ->values();

        $doctors = Doctor::with('category')
            ->whereIn('id', $doctorIds)
            ->get()
            ->map(function (Doctor $doctor) use ($patientId) {
                $lastMessage = Message::where(function ($query) use ($patientId, $doctor) {
                    $query->where('sender_id', $patientId)
                        ->where('receiver_id', $doctor->user_id);
                })->orWhere(function ($query) use ($patientId, $doctor) {
                    $query->where('sender_id', $doctor->user_id)
                        ->where('receiver_id', $patientId);
                })
                    ->latest()
                    ->first();

                $doctor->setRelation('last_message', $lastMessage);
                $doctor->unread_messages_count = Message::where('sender_id', $doctor->user_id)
                    ->where('receiver_id', $patientId)
                    ->where('is_read', false)
                    ->count();

                return $doctor;
            })
            ->sortByDesc(fn (Doctor $doctor) => optional($doctor->last_message)->created_at)
            ->values();

        return view('patient.messages.index', compact('doctors'));
    }

    public function showDoctorMsg(Doctor $doctor)
    {
        $patientId = auth()->id();
        $messages = Message::where(function($q) use ($patientId, $doctor) {
            $q->where('sender_id', $patientId)
              ->where('receiver_id', $doctor->user_id);
        })->orWhere(function($q) use ($patientId, $doctor) {
            $q->where('sender_id', $doctor->user_id)
              ->where('receiver_id', $patientId);
        })
        ->orderBy('created_at', 'asc')
        ->get();

        Message::where('sender_id', $doctor->user_id)
            ->where('receiver_id', $patientId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('patient.messages.chat', compact('doctor', 'messages'));
    }

    public function storeDoctorMsg(Request $request, Doctor $doctor)
    {   
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = new Message();
        $message->sender_id = auth()->id();
        $message->receiver_id = $doctor->user_id;
        $message->message = $request->input('message');
        $message->save();

        return redirect()->route('patient.messages.show', $doctor)->with('success', 'Message sent successfully.');
    }
}
