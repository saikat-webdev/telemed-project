@extends('layout.dashboard')
@section('title', 'My Appointments')            
@section('content')
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Your Appointments</h2>
                    <p class="text-gray-500 text-sm">Manage and track your scheduled visits</p>
                </div>
                <button class="bg-blue-600 text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-blue-700 shadow-sm transition-all">
                    + Book New Appointment
                </button>
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

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase">Doctor</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase">Date & Time</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase">Type</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase">Status</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @if(isset($appointments) && $appointments->count() > 0)
                        @foreach($appointments as $appointment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <img src="https://img.freepik.com/premium-vector/default-avatar-profile-icon-social-media-user-image-gray-avatar-icon-blank-profile-silhouette-vector-illustration_561158-3407.jpg" class="w-10 h-10 rounded-full border">
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm">{{ $appointment->doctor->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $appointment->doctor->specialization }}</p>
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
                                <span class="text-xs {{ $appointment->status_color }} px-3 py-1 rounded-full font-bold">{{ $appointment->status_label }}</span>
                            </td>
                            <td class="p-4">
                                <button class="text-gray-400 hover:text-blue-600 transition-colors">Details →</button>
                                <button class="flex items-center gap-2 text-gray-400 hover:text-blue-600 transition-colors group">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                    </svg>
                                    @if($appointment->status == 1)
                                    <a href="{{ route('patients.messages.show', $appointment->doctor) }}"><span class="text-sm font-medium">Chat</span></a>
                                    @endif
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="p-4 bg-gray-50 text-center">
                    <button class="text-sm text-blue-600 font-semibold hover:underline">View Transaction History</button>
                </div>
            </div>
    
    @endsection