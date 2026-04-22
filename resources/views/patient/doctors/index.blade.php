@extends('patient.layout.dashboard')

@section('title', 'Doctors | HealthHub')

@section('content')
<div x-data="{ openModal: false, selectedDoctorName: '', selectedDoctorId: '' }" class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Find a Specialist</h2>
            <p class="text-gray-500 text-sm mt-1">Showing {{ $doctors->count() }} doctors available for consultation.</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
        <form action="{{ route('patient.doctors.index') }}" method="GET" class="flex flex-wrap md:flex-nowrap items-end gap-5">
            <div class="flex-1 min-w-[280px]">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Doctor Name</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-300 group-focus-within:text-blue-500 transition-colors"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-200 text-sm placeholder-gray-400"
                        placeholder="e.g. Dr. Jameson">
                </div>
            </div>

            <div class="w-full md:w-72">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Specialization</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-stethoscope text-gray-300 group-focus-within:text-blue-500 transition-colors"></i>
                    </div>
                    <select name="category_id"
                        class="block w-full pl-10 pr-10 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-200 text-sm appearance-none bg-white cursor-pointer">
                        <option value="">All Specialties</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-300 text-xs"></i>
                    </div>
                </div>
            </div>

            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit"
                    class="flex-1 md:flex-none px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition-all duration-200 active:scale-95 flex items-center justify-center">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                @if(request('search') || request('category_id'))
                    <a href="{{ route('patient.doctors.index') }}"
                        class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl transition-all duration-200 flex items-center justify-center"
                        title="Clear Filters">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
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
            class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl flex justify-between items-center shadow-sm"
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

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($doctors as $doctor)
            <div class="bg-white rounded-2xl border border-gray-100 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300 group p-6 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>

                <div class="relative inline-block mb-4">
                    <div class="w-24 h-24 rounded-2xl mx-auto bg-blue-100 text-blue-600 flex items-center justify-center text-3xl font-black shadow-sm group-hover:scale-105 transition-transform duration-300">
                        {{ substr($doctor->name, 0, 1) }}
                    </div>
                    <div class="absolute -bottom-2 -right-2 bg-green-500 border-4 border-white w-6 h-6 rounded-full" title="Available Now"></div>
                </div>

                <div class="mb-3">
                    <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-blue-600 bg-blue-50 rounded-full">
                        {{ $doctor->category->name ?? 'General Specialist' }}
                    </span>
                </div>

                <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors">{{ $doctor->name }}</h3>

                <div class="pt-4 border-t border-gray-50 flex items-center justify-between gap-3">
                    <span class="text-xs font-bold text-gray-700">Rs. {{ number_format($doctor->fees ?? 0, 0) }} <span class="text-gray-400 font-normal">/ session</span></span>
                    <button
                        @click="openModal = true; selectedDoctorName = '{{ $doctor->name }}'; selectedDoctorId = '{{ $doctor->id }}'"
                        class="px-4 py-2 bg-gray-900 text-white text-xs font-bold rounded-lg hover:bg-blue-600 transition-colors shadow-md">
                        Book Now
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-user-md text-gray-300 text-3xl"></i>
                </div>
                <h3 class="text-gray-900 font-bold text-xl">No Doctors Found</h3>
                <p class="text-gray-500 mt-1">Try adjusting your search or filters to find what you're looking for.</p>
                <a href="{{ route('patient.doctors.index') }}" class="mt-6 text-blue-600 font-bold hover:underline">Clear all filters</a>
            </div>
        @endforelse
    </div>

    <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="openModal = false"></div>

        <div class="flex items-center justify-center min-h-screen p-4">
            <div
                class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 relative transform transition-all"
                @click.away="openModal = false"
                x-show="openModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0">

                <button @click="openModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">Book Appointment</h3>
                    <p class="text-gray-500 text-sm">Booking for <span class="text-blue-600 font-semibold" x-text="selectedDoctorName"></span></p>
                </div>

                <form action="{{ route('patient.appointments.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="doctor_id" :value="selectedDoctorId">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Select Date</label>
                        <input type="date" name="appointment_date" min="{{ date('Y-m-d') }}" class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 bg-gray-50 border" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Available Slots</label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach(['09:00', '10:30', '13:00', '14:30', '16:00', '17:30'] as $time)
                                <label class="cursor-pointer">
                                    <input type="radio" name="appointment_time" value="{{ $time }}" class="peer hidden" required>
                                    <div class="text-center py-2 px-1 border border-gray-200 rounded-lg text-sm peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-blue-50 transition-all">
                                        {{ \Carbon\Carbon::createFromFormat('H:i', $time)->format('h:i A') }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Reason for Visit</label>
                        <textarea rows="3" name="comment" class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 bg-gray-50 border" placeholder="Briefly describe your concern..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all active:scale-95">
                        Confirm Appointment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
