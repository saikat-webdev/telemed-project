@extends('patient.layout.dashboard')

@section('title', 'Messages | HealthHub')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800">Messages</h2>
            <p class="text-gray-500 text-sm font-medium mt-1">Continue conversations with your doctors and keep follow-ups organized.</p>
        </div>
        <a href="{{ route('patient.doctors.index') }}" class="bg-violet-600 hover:bg-violet-700 text-white px-5 py-2.5 rounded-xl font-semibold shadow-sm transition-all">
            Find Doctors
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="divide-y divide-gray-100">
            @forelse($doctors as $doctor)
                <a href="{{ route('patient.messages.show', $doctor) }}" class="flex items-center justify-between gap-4 p-5 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-12 h-12 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center font-black shrink-0">
                            {{ substr($doctor->name, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-gray-800 truncate">{{ $doctor->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $doctor->category->name ?? 'Specialist' }}</p>
                            <p class="text-sm text-gray-400 truncate mt-1">
                                {{ optional($doctor->last_message)->message ?: 'No messages yet. Start the conversation.' }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        @if(optional($doctor->last_message)->created_at)
                            <p class="text-xs text-gray-400">{{ $doctor->last_message->created_at->diffForHumans() }}</p>
                        @endif
                        @if(($doctor->unread_messages_count ?? 0) > 0)
                            <span class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 mt-2 bg-blue-600 text-white text-xs font-bold rounded-full">
                                {{ $doctor->unread_messages_count }}
                            </span>
                        @endif
                    </div>
                </a>
            @empty
                <div class="p-16 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="far fa-comments text-gray-300 text-2xl"></i>
                    </div>
                    <h4 class="text-gray-800 font-bold">No conversations yet</h4>
                    <p class="text-gray-400 text-sm mt-1">Once you book an appointment, you can message the doctor from your appointments list.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
