@extends('patient.layout.dashboard')

@section('title', 'Doctors | TeleHealth')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Find a Specialist</h2>
            <p class="text-gray-500 text-sm mt-1">Showing {{ $doctors->count() }} specialized doctors available for consultation.</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-10">
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
                       class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl transition-all duration-200 flex items-center justify-center" title="Clear Filters">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($doctors as $doctor)
            <div class="bg-white rounded-2xl border border-gray-100 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300 group p-6 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="relative inline-block mb-4">
                    <img src="https://i.pravatar.cc/150?u={{ $doctor->name }}&background=F0F7FF&color=3B82F6&bold=true" 
                         class="w-24 h-24 rounded-2xl mx-auto object-cover shadow-sm group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute -bottom-2 -right-2 bg-green-500 border-4 border-white w-6 h-6 rounded-full" title="Available Now"></div>
                </div>
                
                <div class="mb-3">
                    <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-blue-600 bg-blue-50 rounded-full">
                        {{ $doctor->category->name ?? 'General Specialist' }}
                    </span>
                </div>
                
                <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors">{{ $doctor->name }}</h3>
                <!-- <p class="text-gray-400 text-xs font-medium mb-6 flex items-center justify-center uppercase">
                    <i class="fas fa-award mr-1 text-yellow-400"></i> {{ $doctor->specialization ?? 'Senior Consultant' }}
                </p> -->
                
                <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-700">₹ 1000 <span class="text-gray-400 font-normal">/ session</span></span>
                    <a href="#" class="px-4 py-2 bg-gray-900 text-white text-xs font-bold rounded-lg hover:bg-blue-600 transition-colors shadow-md">
                        Book Now
                    </a>
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
</div>
@endsection