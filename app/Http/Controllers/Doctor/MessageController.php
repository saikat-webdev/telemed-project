<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class MessageController extends Controller
{
    protected function currentDoctor(): Doctor
    {
        return Doctor::where('user_id', auth()->id())->firstOrFail();
    }

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
        $doctor = $this->currentDoctor();
        $search = trim((string) $request->string('search'));

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
            ->filter(function (User $patient) use ($search) {
                if ($search === '') {
                    return true;
                }

                return str_contains(strtolower($patient->name), strtolower($search))
                    || str_contains(strtolower($patient->email), strtolower($search))
                    || str_contains(strtolower(optional($patient->last_message)->message ?? ''), strtolower($search));
            })
            ->sortByDesc(fn (User $patient) => optional($patient->last_message)->created_at)
            ->values();

        $patients = $this->paginateCollection($patients, 10);

        return view('doctor.messages.index', compact('patients'));
    }

    public function showPatientMsg(Request $request, User $patient)
    {
        $doctor = $this->currentDoctor();
        $search = trim((string) $request->string('search'));

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
        });

        if ($search !== '') {
            $messages->where('message', 'like', '%'.$search.'%');
        }

        $messages = $messages
            ->orderBy('created_at', 'asc')
            ->paginate(25)
            ->withQueryString();

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
