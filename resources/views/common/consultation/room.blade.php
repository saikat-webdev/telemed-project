@extends(auth()->user()->hasRole('doctor') ? 'doctor.layout.structure' : 'patient.layout.dashboard')

@section('content')
<div class="h-[calc(100vh-120px)] flex flex-col gap-4">
    {{-- Top Bar --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center font-bold">
                <i class="fas fa-video"></i>
            </div>
            <div>
                <h2 class="font-black text-gray-800">
                    {{ auth()->user()->hasRole('doctor') ? 'Patient: ' . $appointment->patient->name : 'Doctor: ' . $appointment->doctor->name }}
                </h2>
                <span class="text-[10px] text-green-500 font-bold uppercase tracking-widest animate-pulse">● Live Session</span>
            </div>
        </div>
        <a href="{{ auth()->user()->hasRole('doctor') ? route('doctor.dashboard') : route('patient.dashboard.index') }}" 
           class="text-gray-400 hover:text-red-500 font-bold text-xs uppercase tracking-widest transition-colors">
           Leave Room
        </a>
    </div>

    <div class="flex-1 grid grid-cols-1 lg:grid-cols-4 gap-6 min-h-0">
        {{-- Jitsi Video --}}
        <div class="lg:col-span-3 bg-[#111] rounded-3xl overflow-hidden shadow-2xl relative border-4 border-white">
            <div id="jitsi-container" class="h-full w-full"></div>
        </div>

        {{-- Sidebar based on Role --}}
        <div class="lg:col-span-1 space-y-4">
            @if(auth()->user()->hasRole('doctor'))
                {{-- Doctor sees Note Taking Form --}}
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm h-full">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Clinical Notes</h3>
                    <textarea class="w-full border-none bg-gray-50 rounded-xl p-4 text-sm focus:ring-2 focus:ring-blue-100 mb-4" rows="12" placeholder="Start typing diagnosis..."></textarea>
                    <button class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold text-xs uppercase tracking-widest shadow-lg shadow-blue-100">Save & Complete</button>
                </div>
            @else
                {{-- Patient sees Appointment Info --}}
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm h-full">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Meeting Info</h3>
                    <div class="p-4 bg-blue-50 rounded-xl mb-4">
                        <p class="text-xs text-blue-700 font-medium">Please stay in the room until the doctor ends the session to receive your digital prescription.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://meet.jit.si/external_api.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        const domain = 'meet.jit.si';
        const options = {
            roomName: '{{ $roomName }}',
            width: '100%',
            height: '100%',
            parentNode: document.querySelector('#jitsi-container'),
            configOverwrite: {
                prejoinPageEnabled: false,
                disableInviteFunctions: true,
                doNotStoreRoom: false,
                startWithVideoMuted: false,
                enableNoisyMicDetection: false
            },
            interfaceConfigOverwrite: {
                MOBILE_APP_PROMO: false 
            }
        };
        const api = new JitsiMeetExternalAPI(domain, options);
    });
</script>
@endsection