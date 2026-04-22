@extends('doctor.layout.structure')

@section('title', 'Chat with ' . $patient->name)

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 flex overflow-hidden" style="height: calc(100vh - 160px);">
    <div class="w-80 border-r border-gray-100 flex-col bg-gray-50/50 hidden lg:flex">
        <div class="p-4 border-b border-gray-100 bg-white flex items-center justify-between">
            <h3 class="font-bold text-gray-800">Messages</h3>
            <a href="{{ route('doctor.messages.index') }}" class="text-sm text-violet-600 font-semibold hover:text-violet-700">All Chats</a>
        </div>
        <div class="flex-1 overflow-y-auto p-4">
            <div class="p-4 flex items-center gap-3 bg-blue-50 border border-blue-100 rounded-2xl">
                <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center font-black">
                    {{ substr($patient->name, 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <p class="font-bold text-sm text-gray-800 truncate">{{ $patient->name }}</p>
                    <p class="text-xs text-blue-600 font-medium">{{ $patient->email }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-1 flex flex-col bg-white">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-violet-100 text-violet-700 flex items-center justify-center font-black">
                    {{ substr($patient->name, 0, 1) }}
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">{{ $patient->name }}</h4>
                    <p class="text-xs text-gray-500">{{ $patient->email }}</p>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50/30" id="message-container">
            @forelse($messages as $msg)
                <div class="flex {{ $msg->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[70%] {{ $msg->sender_id == auth()->id() ? 'bg-blue-600 text-white rounded-l-2xl rounded-tr-2xl' : 'bg-white border border-gray-200 text-gray-800 rounded-r-2xl rounded-tl-2xl' }} p-3 shadow-sm">
                        <p class="text-sm">{{ $msg->message }}</p>
                        <span class="text-[10px] block mt-1 opacity-70 text-right">
                            {{ $msg->created_at->format('h:i A') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center mt-10">
                    <div class="bg-blue-50 text-blue-600 inline-block p-4 rounded-full mb-2">Hi</div>
                    <p class="text-gray-400 text-sm">Start your conversation with {{ $patient->name }}</p>
                </div>
            @endforelse
        </div>

        <div class="p-4 border-t border-gray-100">
            <form action="{{ route('doctor.messages.store', $patient) }}" method="POST" class="flex gap-3">
                @csrf
                <input type="text" name="message" placeholder="Type your message here..." required
                    class="flex-1 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 transition-all flex items-center gap-2">
                    Send
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const container = document.getElementById('message-container');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
</script>
@endpush
