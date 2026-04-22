@extends('patient.layout.dashboard')

@section('title', 'AI Symptom Checker | HealthHub')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800">AI Symptom Checker</h2>
            <p class="text-gray-500 text-sm font-medium mt-1">Describe your symptoms in plain language and get a quick guidance-based health suggestion.</p>
        </div>
        <a href="{{ route('patient.doctors.index') }}" class="bg-violet-600 hover:bg-violet-700 text-white px-5 py-2.5 rounded-xl font-semibold shadow-sm transition-all">
            Find a Doctor
        </a>
    </div>

    <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl p-5">
        <p class="font-bold text-sm uppercase tracking-[0.2em]">Medical Disclaimer</p>
        <p class="mt-2 text-sm">
            This AI symptom checker gives general suggestions only. It does not provide a medical diagnosis, treatment plan, or emergency care advice. If symptoms are severe, sudden, or worrying, contact a doctor or emergency services immediately.
        </p>
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

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-bold text-gray-800 text-lg">Describe Your Symptoms</h3>
                <p class="text-sm text-gray-500 mt-1">Example: "I have fever, sore throat, dry cough, and body ache for 2 days."</p>
            </div>

            <form action="{{ route('patient.symptom-checker.index') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div>
                    <label for="symptoms" class="block text-sm font-semibold text-gray-700 mb-2">Symptoms</label>
                    <textarea
                        id="symptoms"
                        name="symptoms"
                        rows="8"
                        class="w-full border-gray-200 rounded-2xl focus:ring-violet-500 focus:border-violet-500 p-4 bg-gray-50 border"
                        placeholder="Describe what you are feeling, how long it has been happening, and anything important like pain, fever, rash, nausea, breathing trouble, swelling, or dizziness."
                    >{{ old('symptoms', $symptoms ?? '') }}</textarea>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" class="bg-violet-600 hover:bg-violet-700 text-white px-6 py-3 rounded-2xl font-bold transition-all shadow-lg shadow-violet-200">
                        Analyze Symptoms
                    </button>
                    <a href="{{ route('patient.symptom-checker.index') }}" class="px-5 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl font-semibold transition-all">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-bold text-gray-800 text-lg">How It Works</h3>
            </div>
            <div class="p-6 space-y-4 text-sm text-gray-600">
                <div class="flex gap-3">
                    <span class="w-8 h-8 rounded-full bg-violet-100 text-violet-700 flex items-center justify-center font-bold shrink-0">1</span>
                    <p>Write your symptoms in normal everyday language.</p>
                </div>
                <div class="flex gap-3">
                    <span class="w-8 h-8 rounded-full bg-violet-100 text-violet-700 flex items-center justify-center font-bold shrink-0">2</span>
                    <p>The system checks symptom patterns against common conditions.</p>
                </div>
                <div class="flex gap-3">
                    <span class="w-8 h-8 rounded-full bg-violet-100 text-violet-700 flex items-center justify-center font-bold shrink-0">3</span>
                    <p>You get likely matches, red-flag warnings, and a suggested specialist category.</p>
                </div>
            </div>
        </div>
    </div>

    @if($analysis)
        <div class="space-y-6">
            @if(!empty($analysis['alerts']))
                <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
                    <h3 class="text-lg font-black text-red-700">Urgent Warning Signs Detected</h3>
                    <div class="mt-3 space-y-2">
                        @foreach($analysis['alerts'] as $alert)
                            <p class="text-sm text-red-700">{{ $alert }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">Possible Matches</h3>
                        <p class="text-sm text-gray-500 mt-1">These are guidance-based suggestions from the symptom text you entered.</p>
                    </div>
                    <a href="{{ route('patient.doctors.index') }}" class="text-sm text-violet-600 font-semibold hover:text-violet-700">Book Consultation</a>
                </div>

                <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-5">
                    @foreach($analysis['conditions'] as $condition)
                        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-5">
                            <div class="flex items-start justify-between gap-3">
                                <h4 class="font-black text-gray-800 leading-snug">{{ $condition['name'] }}</h4>
                                <span class="px-3 py-1 rounded-full bg-violet-100 text-violet-700 text-[10px] font-bold uppercase tracking-widest">
                                    {{ $condition['category'] }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-3">{{ $condition['summary'] }}</p>
                            @if(!empty($condition['matched_keywords']))
                                <div class="mt-4">
                                    <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 mb-2">Matched Clues</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($condition['matched_keywords'] as $keyword)
                                            <span class="px-2.5 py-1 rounded-full bg-white border border-gray-200 text-xs text-gray-600">{{ $keyword }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-indigo-950 text-indigo-50 rounded-2xl p-6">
                <h3 class="text-lg font-black">Recommended Next Step</h3>
                <p class="mt-3 text-sm text-indigo-100">{{ $analysis['next_step'] }}</p>
            </div>
        </div>
    @endif
</div>
@endsection
