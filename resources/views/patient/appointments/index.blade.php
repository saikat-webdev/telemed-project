@extends('patient.layout.dashboard')
@section('title', 'My Appointments | TeleHealth')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Your Appointments</h2>
        <p class="text-gray-500 text-sm">Manage and track your scheduled visits</p>
    </div>
    <a href="{{ route('patient.doctors.index') }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-blue-700 shadow-sm transition-all">
        + Book New Appointment
    </a>
</div>

<div class="grid grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-gray-500 text-sm font-medium">Upcoming</p>
        <h3 class="text-2xl font-bold text-green-600">{{ $appointments->where('status', 1)->count() }}</h3>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-gray-500 text-sm font-medium">Pending</p>
        <h3 class="text-2xl font-bold text-yellow-600">{{ $appointments->where('status', 0)->count() }}</h3>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-gray-500 text-sm font-medium">Cancelled</p>
        <h3 class="text-2xl font-bold text-red-500">{{ $appointments->where('status', 2)->count() }}</h3>
    </div>
</div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside text-sm font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div x-data="{ show: true }" 
                    x-show="show" 
                    x-init="setTimeout(() => show = false, 5000)"
                    class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex justify-between items-center shadow-sm"
                    x-transition:leave="transition ease-in duration-300">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-700 hover:text-green-900">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="p-4 text-xs font-bold text-gray-500 uppercase">Doctor</th>
                <th class="p-4 text-xs font-bold text-gray-500 uppercase">Date & Time</th>
                <th class="p-4 text-xs font-bold text-gray-500 uppercase">Type</th>
                <th class="p-4 text-xs font-bold text-gray-500 uppercase">Status</th>
                <th class="p-4 text-xs font-bold text-gray-500 uppercase text-center">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @if(isset($appointments) && $appointments->count() > 0)
                @foreach($appointments as $appointment)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/150?u={{ $appointment->doctor->name }}" class="w-10 h-10 rounded-full border">
                            <div>
                                <p class="font-bold text-gray-800 text-sm">{{ $appointment->doctor->name }}</p>
                                <p class="text-xs text-gray-500">{{ $appointment->doctor->category->name }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-4">
                        <p class="text-sm font-medium text-gray-800">{{ $appointment->appointment_date }}</p>
                        <p class="text-xs text-gray-500">{{ $appointment->appointment_time }}</p>
                    </td>
                    <td class="p-4">
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">Video Call</span>
                    </td>
                    <td class="p-4">
                        <span class="text-xs {{ $appointment->status_color }} px-3 py-1 rounded-full font-bold">
                            {{ $appointment->status_label }}
                        </span>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center justify-center gap-3">
                            {{-- Action for Confirmed Status (Status 1) --}}
                            @if($appointment->status == 1)
                                <a href="{{ route('patients.messages.show', $appointment->doctor) }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                    </svg>
                                </a>
                                <form action="{{ route('patient.appointments.pay') }}" method="post" class="m-0">
                                    @csrf
                                    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                                    <input type="hidden" name="amount" value="1000">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 px-4 rounded-lg transition shadow-md shadow-blue-100">
                                        Pay Now (₹1000)
                                    </button>
                                </form>
                            @endif
                            @if($appointment->status == 2)
                                <a href="{{ route('patients.messages.show', $appointment->doctor) }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                    </svg>
                                </a>
                            @endif
                            {{-- Action for Completed Status (Status 3) --}}
                            @if($appointment->status == 3)
                                @if($appointment->review)
                                    <div class="flex flex-col items-center">
                                        <div class="flex items-center gap-0.5">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $appointment->review->rating)
                                                    {{-- Filled Golden Star --}}
                                                    <span class="text-yellow-400 text-sm">★</span>
                                                @else
                                                    {{-- Empty Gray Star --}}
                                                    <span class="text-gray-300 text-sm">★</span>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                @else
                                    <button onclick="openReviewModal({{ $appointment->id }}, '{{ $appointment->doctor->name }}', '{{ $appointment->doctor->id }}')" 
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-1">
                                        <i class="fas fa-pen-nib"></i> Review
                                    </button>
                                @endif
                            @endif
                            @if($appointment->status == 2)
                                <a href="{{ route(auth()->user()->hasRole('doctor') ? 'doctor.consultation' : 'patient.consultation', $appointment->id) }}" 
                                class="bg-[#1e293b] hover:bg-black text-white px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-gray-200 inline-block text-center">
                                    Join Call
                                </a>
                            @endif
                            
                        </div>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-500">No appointments found.</td>
                </tr>
            @endif
        </tbody>
    </table>
    <div class="p-4 bg-gray-50 text-center border-t border-gray-100">
        <button class="text-sm text-blue-600 font-semibold hover:underline">View Transaction History</button>
    </div>
</div>

<div id="reviewModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 z-50">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Review this Appointment with Dr. <span id="modalDoctorName"></span></h3>
                <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>

            <form action="{{ route('patient.appointments.review.store') }}" method="POST">
                @csrf
                <input type="hidden" name="appointment_id" id="modal_app_id">
                <input type="hidden" name="doctor_id" id="modal_doctor_id">
                
                <div class="mb-4">
                    <!-- <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label> -->
                    <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">How would you rate your experience?</label>
                    <div class="flex flex-row-reverse justify-end items-center gap-1">
                        {{-- Radio buttons in reverse order for CSS sibling selector logic --}}
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="hidden peer" required>
                            <label for="star{{ $i }}" class="cursor-pointer text-3xl text-gray-300 peer-hover:text-yellow-400 peer-checked:text-yellow-500 transition-colors">
                                ★
                            </label>
                        @endfor
                    </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Your Feedback</label>
                    <textarea name="review" rows="3" class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-yellow-400 outline-none" placeholder="Share your experience..."></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeReviewModal()" class="px-4 py-2 text-gray-500 font-medium">Cancel</button>
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-xl font-bold transition-all shadow-lg shadow-yellow-100">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openReviewModal(appId, doctorName, doctorId) {
        document.getElementById('modal_app_id').value = appId;
        document.getElementById('modal_doctor_id').value = doctorId;
        document.getElementById('modalDoctorName').innerText = doctorName;
        document.getElementById('reviewModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close on outside click
    window.onclick = function(event) {
        let modal = document.getElementById('reviewModal');
        if (event.target == modal) {
            closeReviewModal();
        }
    }
</script>
@endsection