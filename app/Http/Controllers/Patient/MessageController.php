<?php

namespace App\Http\Controllers\Patient;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class MessageController extends \App\Http\Controllers\Controller
{
    protected function paginateCollection($items, int $perPage = 10): LengthAwarePaginator
    {
        $page = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($items)->values();
        $results = $collection->forPage($page, $perPage)->values();

        return new LengthAwarePaginator(
            $results,
            $collection->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public function index(Request $request)
    {
        $patientId = auth()->id();
        $search = trim((string) $request->string('search'));
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
            ->filter(function (Doctor $doctor) use ($search) {
                if ($search === '') {
                    return true;
                }

                return str_contains(strtolower($doctor->name), strtolower($search))
                    || str_contains(strtolower($doctor->category->name ?? ''), strtolower($search))
                    || str_contains(strtolower(optional($doctor->last_message)->message ?? ''), strtolower($search));
            })
            ->sortByDesc(fn (Doctor $doctor) => optional($doctor->last_message)->created_at)
            ->values();

        $doctors = $this->paginateCollection($doctors, 10);

        return view('patient.messages.index', compact('doctors'));
    }

    public function showDoctorMsg(Request $request, Doctor $doctor)
    {
        $patientId = auth()->id();
        $search = trim((string) $request->string('search'));
        $messages = Message::where(function ($q) use ($patientId, $doctor) {
            $q->where('sender_id', $patientId)
                ->where('receiver_id', $doctor->user_id);
        })->orWhere(function ($q) use ($patientId, $doctor) {
            $q->where('sender_id', $doctor->user_id)
                ->where('receiver_id', $patientId);
        });

        if ($search !== '') {
            $messages->where('message', 'like', '%'.$search.'%');
        }

        $messages = $messages
            ->orderBy('created_at', 'asc')
            ->paginate(25)
            ->withQueryString();

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

        $message = new Message;
        $message->sender_id = auth()->id();
        $message->receiver_id = $doctor->user_id;
        $message->message = $request->input('message');
        $message->save();

        return redirect()->route('patient.messages.show', $doctor)->with('success', 'Message sent successfully.');
    }
}
