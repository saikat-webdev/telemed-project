@extends('doctor.layout.structure')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <h2 class="text-2xl font-extrabold text-gray-800">Edit Profile</h2>
        <p class="text-gray-500 text-sm mt-1">Keep your contact, specialty, and consultation fee details current.</p>
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

    <form action="{{ route('doctor.profile.update') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
            <input type="text" name="name" value="{{ old('name', $doctor->name) }}" class="w-full rounded-xl border-gray-200 focus:border-violet-500 focus:ring-violet-500">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email', $doctor->email) }}" class="w-full rounded-xl border-gray-200 focus:border-violet-500 focus:ring-violet-500">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $doctor->phone) }}" class="w-full rounded-xl border-gray-200 focus:border-violet-500 focus:ring-violet-500">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Specialization</label>
            <select name="specialization" class="w-full rounded-xl border-gray-200 focus:border-violet-500 focus:ring-violet-500">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('specialization', $doctor->specialization) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Consultation Fee</label>
            <input type="number" step="0.01" min="0" name="fees" value="{{ old('fees', $doctor->fees) }}" class="w-full rounded-xl border-gray-200 focus:border-violet-500 focus:ring-violet-500">
        </div>
        <div class="md:col-span-2 flex justify-end">
            <button type="submit" class="bg-violet-600 hover:bg-violet-700 text-white px-6 py-3 rounded-xl font-bold shadow-sm">Save Changes</button>
        </div>
    </form>
</div>
@endsection
