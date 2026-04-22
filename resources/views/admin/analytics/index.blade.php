@extends('admin.layout.dashboard')

@section('title', 'Analytics')
@section('page-title', 'Analytics')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Analytics Dashboard</h2>
        <p class="text-gray-500 mt-1">View detailed statistics and insights</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Total Revenue</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">${{ number_format($totalRevenue) }}</p>
            <p class="text-sm text-green-500 mt-2">All time consultations</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Monthly Revenue</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">${{ number_format($monthlyRevenue) }}</p>
            <p class="text-sm text-gray-400 mt-2">Current month</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Completed Appointments</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $statusCounts[3] ?? 0 }}</p>
            <p class="text-sm text-green-500 mt-2">Successful</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Pending Appointments</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $statusCounts[0] ?? 0 }}</p>
            <p class="text-sm text-yellow-500 mt-2">Awaiting confirmation</p>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Status Distribution --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Distribution</h3>
            <div class="space-y-4">
                @php
                $statusLabels = [
                    0 => ['label' => 'Pending', 'color' => 'bg-yellow-500'],
                    1 => ['label' => 'Confirmed', 'color' => 'bg-blue-500'],
                    2 => ['label' => 'Fees Paid', 'color' => 'bg-purple-500'],
                    3 => ['label' => 'Completed', 'color' => 'bg-green-500'],
                    4 => ['label' => 'Cancelled', 'color' => 'bg-red-500'],
                ];
                $total = $statusCounts->sum() ?: 1;
                @endphp
                @foreach($statusCounts as $status => $count)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">{{ $statusLabels[$status]['label'] ?? 'Unknown' }}</span>
                        <span class="font-medium">{{ $count }} ({{ round(($count / $total) * 100) }}%)</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="{{ $statusLabels[$status]['color'] }} h-3 rounded-full transition-all duration-500" style="width: {{ ($count / $total) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Monthly Appointments --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Appointments ({{ now()->year }})</h3>
            <div class="flex items-end gap-2 h-48">
                @for($i = 1; $i <= 12; $i++)
                @php
                $count = $monthlyAppointments[$i] ?? 0;
                $maxCount = $monthlyAppointments->max() ?: 1;
                $height = max(($count / $maxCount) * 100, 5);
                @endphp
                <div class="flex-1 flex flex-col items-center gap-1">
                    <span class="text-xs text-gray-500">{{ $count }}</span>
                    <div class="w-full bg-orange-500 rounded-t transition-all duration-500" style="height: {{ $height }}%"></div>
                    <span class="text-xs text-gray-400">{{ Date('M', mktime(0, 0, 0, $i, 1)) }}</span>
                </div>
                @endfor
            </div>
        </div>
    </div>

    {{-- Top Doctors --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Top Performing Doctors</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Specialization</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Appointments</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($topDoctors as $index => $doctor)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-bold rounded-full {{ $index === 0 ? 'bg-yellow-100 text-yellow-700' : ($index === 1 ? 'bg-gray-100 text-gray-700' : ($index === 2 ? 'bg-orange-100 text-orange-700' : 'bg-gray-50 text-gray-500')) }}">
                                #{{ $index + 1 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ substr($doctor->name, 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-gray-800">{{ $doctor->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $doctor->category->name ?? 'General' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-800">{{ $doctor->appointments_count }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-400">No data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection