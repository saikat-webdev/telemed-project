@extends('layout.dashboard')

@section('title', 'Chat with ' . $doctor->name)

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 flex overflow-hidden" style="height: calc(100vh - 160px);">
    
    <div class="w-80 border-r border-gray-100 flex flex-col bg-gray-50/50">
        <div class="p-4 border-b border-gray-100 bg-white">
            <h3 class="font-bold text-gray-800">Messages</h3>
        </div>
        <div class="flex-1 overflow-y-auto">
            <div class="p-4 flex items-center gap-3 bg-blue-50 border-r-4 border-blue-600 cursor-pointer">
                <img src="https://img.freepik.com/premium-vector/default-avatar-profile-icon-social-media-user-image-gray-avatar-icon-blank-profile-silhouette-vector-illustration_561158-3407.jpg" class="w-12 h-12 rounded-full border-2 border-white">
                <div class="overflow-hidden">
                    <p class="font-bold text-sm text-gray-800 truncate">{{ $doctor->name }}</p>
                    <p class="text-xs text-blue-600 font-medium">Active Now</p>
                </div>
            </div>
            </div>
    </div>

    <div class="flex-1 flex flex-col bg-white">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <img src="https://img.freepik.com/premium-vector/default-avatar-profile-icon-social-media-user-image-gray-avatar-icon-blank-profile-silhouette-vector-illustration_561158-3407.jpg" class="w-10 h-10 rounded-full">
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">{{ $doctor->name }}</h4>
                    <p class="text-xs text-gray-500">{{ $doctor->specialization }}</p>
                </div>
            </div>
            <button class="text-gray-400 hover:text-gray-600">
                <i class="icon">⋮</i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50/30" id="message-container">
            @forelse($messages as $msg)
                <div class="flex {{ $msg->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[70%] {{ $msg->sender_id == auth()->id() ? 'bg-blue-600 text-white rounded-l-xl rounded-tr-xl' : 'bg-white border border-gray-200 text-gray-800 rounded-r-xl rounded-tl-xl' }} p-3 shadow-sm">
                        <p class="text-sm">{{ $msg->message }}</p>
                        <span class="text-[10px] block mt-1 opacity-70 text-right">
                            {{ $msg->created_at->format('H:i') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center mt-10">
                    <div class="bg-blue-50 text-blue-600 inline-block p-4 rounded-full mb-2 italic">👋</div>
                    <p class="text-gray-400 text-sm">Start your conversation with {{ $doctor->name }}</p>
                </div>
            @endforelse
        </div>

        <div class="p-4 border-t border-gray-100">
            <form action="{{ route('patients.messages.store', $doctor->id) }}" method="POST" class="flex gap-3">
                @csrf
                <input type="text" name="message" placeholder="Type your message here..." required
                    class="flex-1 border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition-all flex items-center gap-2">
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
    // Auto-scroll to bottom of chat
    const container = document.getElementById('message-container');
    container.scrollTop = container.scrollHeight;
</script>
@endpush