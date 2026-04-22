<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected function currentDoctor(): Doctor
    {
        return Doctor::where('user_id', auth()->id())->firstOrFail();
    }

    public function index()
    {
        $doctor = $this->currentDoctor();

        $patients = User::whereHas('appointments', function ($query) use ($doctor) {
            $query->where('doctor_id', $doctor->id);
        })
            ->get()
            ->map(function (User $patient) use ($doctor) {
                $lastMessage = Message::where(function ($query) use ($doctor, $patient) {
                    $query->where('sender_id', $doctor->user_id)
                        ->where('receiver_id', $patient->id);
                })->orWhere(function ($query) use ($doctor, $patient) {
                    $query->where('sender_id', $patient->id)
                        ->where('receiver_id', $doctor->user_id);
                })
                    ->latest()
                    ->first();

                $patient->last_message = $lastMessage;
                $patient->unread_messages_count = Message::where('sender_id', $patient->id)
                    ->where('receiver_id', $doctor->user_id)
                    ->where('is_read', false)
                    ->count();

                return $patient;
            })
            ->sortByDesc(fn (User $patient) => optional($patient->last_message)->created_at)
            ->values();

        return view('doctor.messages.index', compact('patients'));
    }

    public function showPatientMsg(User $patient)
    {
        $doctor = $this->currentDoctor();

        abort_unless(
            $patient->appointments()->where('doctor_id', $doctor->id)->exists(),
            403
        );

        $messages = Message::where(function ($query) use ($doctor, $patient) {
            $query->where('sender_id', $doctor->user_id)
                ->where('receiver_id', $patient->id);
        })->orWhere(function ($query) use ($doctor, $patient) {
            $query->where('sender_id', $patient->id)
                ->where('receiver_id', $doctor->user_id);
        })
            ->orderBy('created_at', 'asc')
            ->get();

        Message::where('sender_id', $patient->id)
            ->where('receiver_id', $doctor->user_id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('doctor.messages.chat', compact('patient', 'doctor', 'messages'));
    }

    public function storePatientMsg(Request $request, User $patient)
    {
        $doctor = $this->currentDoctor();

        abort_unless(
            $patient->appointments()->where('doctor_id', $doctor->id)->exists(),
            403
        );

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'sender_id' => $doctor->user_id,
            'receiver_id' => $patient->id,
            'message' => $request->input('message'),
        ]);

        return redirect()
            ->route('doctor.messages.show', $patient)
            ->with('success', 'Message sent successfully.');
    }
}
