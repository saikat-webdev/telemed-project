@extends('patient.layout.dashboard')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <h2 class="text-2xl font-extrabold text-gray-800">Medical History</h2>
        <p class="text-gray-500 text-sm mt-1">Store allergies, past conditions, medications, and other notes you want doctors to know.</p>
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
        <div class="p-4 bg-green-100 border border-green-300 text-green-700 rounded-xl">{{ session('success') }}</div>
    @endif

    <form action="{{ route('patient.medical-history.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        @csrf
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Record Title</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full rounded-xl border-gray-200 focus:border-violet-500 focus:ring-violet-500" placeholder="Asthma history">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Condition</label>
            <input type="text" name="condition" value="{{ old('condition') }}" class="w-full rounded-xl border-gray-200 focus:border-violet-500 focus:ring-violet-500" placeholder="Asthma">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Recorded Date</label>
            <input type="date" name="recorded_at" value="{{ old('recorded_at') }}" class="w-full rounded-xl border-gray-200 focus:border-violet-500 focus:ring-violet-500">
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Allergies</label>
            <textarea name="allergies" rows="2" class="w-full rounded-xl border-gray-200 focus:border-violet-500 focus:ring-violet-500" placeholder="Dust, peanuts...">{{ old('allergies') }}</textarea>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Current Medications</label>
            <textarea name="current_medications" rows="2" class="w-full rounded-xl border-gray-200 focus:border-violet-500 focus:ring-violet-500" placeholder="Inhaler once daily...">{{ old('current_medications') }}</textarea>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
            <textarea name="notes" rows="3" class="w-full rounded-xl border-gray-200 focus:border-violet-500 focus:ring-violet-500" placeholder="Important context for future consultations...">{{ old('notes') }}</textarea>
        </div>
        <div class="md:col-span-2 flex justify-end">
            <button type="submit" class="bg-violet-600 hover:bg-violet-700 text-white px-6 py-3 rounded-xl font-bold shadow-sm">Add Record</button>
        </div>
    </form>

    <div class="grid grid-cols-1 gap-4">
        @forelse($histories as $history)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <form action="{{ route('patient.medical-history.update', $history) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Title</label>
                        <input type="text" name="title" value="{{ $history->title }}" class="w-full rounded-xl border-gray-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Condition</label>
                        <input type="text" name="condition" value="{{ $history->condition }}" class="w-full rounded-xl border-gray-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Recorded</label>
                        <input type="date" name="recorded_at" value="{{ optional($history->recorded_at)->format('Y-m-d') }}" class="w-full rounded-xl border-gray-200">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Allergies</label>
                        <textarea name="allergies" rows="2" class="w-full rounded-xl border-gray-200">{{ $history->allergies }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Current Medications</label>
                        <textarea name="current_medications" rows="2" class="w-full rounded-xl border-gray-200">{{ $history->current_medications }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Notes</label>
                        <textarea name="notes" rows="3" class="w-full rounded-xl border-gray-200">{{ $history->notes }}</textarea>
                    </div>
                    <div class="md:col-span-2 flex justify-between items-center">
                        <p class="text-xs text-gray-400">Updated {{ $history->updated_at->diffForHumans() }}</p>
                        <div class="flex gap-3">
                            <button type="submit" class="bg-gray-900 hover:bg-black text-white px-4 py-2 rounded-xl text-sm font-semibold">Save</button>
                        </div>
                    </div>
                </form>
                <form action="{{ route('patient.medical-history.destroy', $history) }}" method="POST" class="mt-3 flex justify-end">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-xl text-sm font-semibold">Delete</button>
                </form>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-dashed border-gray-200 p-10 text-center text-gray-400">
                No medical history records yet.
            </div>
        @endforelse
    </div>
</div>
@endsection
