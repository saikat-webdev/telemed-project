@extends('patient.layout.dashboard')

@section('title', 'My Appointments | TeleHealth')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800">My Appointments</h2>
            <p class="text-gray-500 text-sm font-medium mt-1">Track bookings, payments, consultations, and reviews in one place.</p>
        </div>
        <a href="{{ route('patient.doctors.index') }}" class="bg-violet-600 hover:bg-violet-700 text-white px-5 py-2.5 rounded-xl font-semibold shadow-sm transition-all">
            Book Appointment
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
            <p class="text-gray-500 text-sm font-medium">Pending</p>
            <h3 class="text-2xl font-bold text-yellow-600">{{ $appointments->where('status', 0)->count() }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
            <p class="text-gray-500 text-sm font-medium">Confirmed</p>
            <h3 class="text-2xl font-bold text-blue-600">{{ $appointments->where('status', 1)->count() }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
            <p class="text-gray-500 text-sm font-medium">Completed</p>
            <h3 class="text-2xl font-bold text-green-600">{{ $appointments->where('status', 3)->count() }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
            <p class="text-gray-500 text-sm font-medium">Cancelled</p>
            <h3 class="text-2xl font-bold text-red-500">{{ $appointments->where('status', 4)->count() }}</h3>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
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
            class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl flex justify-between items-center shadow-sm"
            x-transition:leave="transition ease-in duration-300">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-green-700 hover:text-green-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/60 border-b border-gray-100">
                    <tr>
                        <th class="p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Doctor</th>
                        <th class="p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Date & Time</th>
                        <th class="p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Reason</th>
                        <th class="p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Status</th>
                        <th class="p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($appointments as $appointment)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm border">
                                        {{ substr($appointment->doctor->name ?? 'D', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm">{{ $appointment->doctor->name ?? 'Doctor unavailable' }}</p>
                                        <p class="text-xs text-gray-500">{{ $appointment->doctor->category->name ?? 'General Specialist' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <p class="text-sm font-medium text-gray-800">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                            </td>
                            <td class="p-4">
                                <p class="text-sm text-gray-700 max-w-xs">{{ $appointment->comment ?: 'General consultation' }}</p>
                                @if($appointment->transaction)
                                    <p class="text-xs text-gray-400 mt-1">Payment Ref: {{ $appointment->transaction->stripe_transaction_id }}</p>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="text-[10px] {{ $appointment->status_color }} px-3 py-1 rounded-full font-black uppercase tracking-widest">
                                    {{ $appointment->status_label }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex flex-wrap justify-end gap-2">
                                    @if((int) $appointment->status === 1)
                                        <form action="{{ route('patient.appointments.pay') }}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 px-4 rounded-xl transition shadow-md shadow-blue-100">
                                                Pay {{ $appointment->doctor && $appointment->doctor->fees ? 'Rs. ' . number_format($appointment->doctor->fees, 0) : '' }}
                                            </button>
                                        </form>
                                        <a href="{{ route('patient.messages.show', $appointment->doctor) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold py-2 px-4 rounded-xl transition">
                                            Message
                                        </a>
                                        <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold py-2 px-4 rounded-xl transition">
                                                Cancel
                                            </button>
                                        </form>
                                    @elseif((int) $appointment->status === 2)
                                        <a href="{{ route('patient.consultation', $appointment->id) }}" class="bg-[#1e293b] hover:bg-black text-white px-4 py-2 rounded-xl text-xs font-bold transition-all">
                                            Join Call
                                        </a>
                                        <a href="{{ route('patient.messages.show', $appointment->doctor) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold py-2 px-4 rounded-xl transition">
                                            Message
                                        </a>
                                    @elseif((int) $appointment->status === 0)
                                        <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold py-2 px-4 rounded-xl transition">
                                                Cancel
                                            </button>
                                        </form>
                                    @elseif((int) $appointment->status === 3)
                                        @if($appointment->review)
                                            <div class="flex items-center gap-1 px-3 py-2 bg-yellow-50 text-yellow-700 rounded-xl text-xs font-bold">
                                                <span>{{ str_repeat('*', (int) $appointment->review->rating) }}</span>
                                                <span>Reviewed</span>
                                            </div>
                                        @else
                                            <button onclick="openReviewModal({{ $appointment->id }}, '{{ addslashes($appointment->doctor->name ?? 'Doctor') }}', '{{ $appointment->doctor->id ?? '' }}')"
                                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all">
                                                Leave Review
                                            </button>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400 font-medium">No actions available</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-10 text-center text-gray-500">No appointments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="reviewModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 z-50">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Review your consultation with Dr. <span id="modalDoctorName"></span></h3>
                <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>

            <form action="{{ route('patient.appointments.review.store') }}" method="POST">
                @csrf
                <input type="hidden" name="appointment_id" id="modal_app_id">
                <input type="hidden" name="doctor_id" id="modal_doctor_id">

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">How would you rate your experience?</label>
                    <div class="flex flex-row-reverse justify-end items-center gap-1">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="hidden" required>
                            <label for="star{{ $i }}" class="cursor-pointer text-3xl text-gray-300 transition-colors hover:text-yellow-400">&#9733;</label>
                        @endfor
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Your Feedback</label>
                    <textarea name="review" rows="3" class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-yellow-400 outline-none" placeholder="Share your experience..." required></textarea>
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
@endsection

@push('scripts')
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

    window.addEventListener('click', function (event) {
        const modal = document.getElementById('reviewModal');
        if (event.target === modal) {
            closeReviewModal();
        }
    });
</script>
@endpush
