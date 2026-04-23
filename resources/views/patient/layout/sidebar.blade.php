<aside class="w-64 bg-gradient-to-b from-indigo-950 via-indigo-900 to-violet-950 text-white flex flex-col fixed h-full shadow-2xl z-50">
    <div class="p-6 flex items-center justify-center border-b border-indigo-700/50">
        <a href="{{ route('patient.dashboard.index') }}" class="hover:opacity-90 transition-all duration-300">
            <div class="flex items-center gap-2">
                <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-fuchsia-600 rounded-2xl flex items-center justify-center shadow-lg shadow-violet-500/30">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-black tracking-tight">
                        <span class="text-violet-400">Health</span><span class="text-white">Hub</span>
                    </h1>
                </div>
            </div>
            <p class="text-[10px] text-indigo-300 text-center tracking-[0.25em] mt-2 font-medium">PATIENT PORTAL</p>
        </a>
    </div>

    <nav class="flex-1 p-4 overflow-y-auto">
        <ul class="space-y-1">
            <li>
                <a href="{{ route('patient.dashboard.index') }}" 
                   class="flex items-center gap-3 p-3.5 rounded-xl transition-all duration-200 group {{ request()->is('patient/dashboard*') ? 'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white shadow-lg shadow-violet-500/25' : 'hover:bg-indigo-800/50 text-indigo-200 hover:text-white hover:translate-x-1' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('patient.doctors.index') }}" 
                   class="flex items-center gap-3 p-3.5 rounded-xl transition-all duration-200 group {{ request()->is('patient/doctors*') ? 'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white shadow-lg shadow-violet-500/25' : 'hover:bg-indigo-800/50 text-indigo-200 hover:text-white hover:translate-x-1' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">Find Doctors</span>
                </a>
            </li>

            <li>
                <a href="{{ route('patient.appointments.index') }}" 
                   class="flex items-center gap-3 p-3.5 rounded-xl transition-all duration-200 group {{ request()->is('patient/appointments*') ? 'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white shadow-lg shadow-violet-500/25' : 'hover:bg-indigo-800/50 text-indigo-200 hover:text-white hover:translate-x-1' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="font-medium">My Appointments</span>
                </a>
            </li>

            <li>
                <a href="{{ route('patient.symptom-checker.index') }}"
                   class="flex items-center gap-3 p-3.5 rounded-xl transition-all duration-200 group {{ request()->is('patient/symptom-checker*') ? 'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white shadow-lg shadow-violet-500/25' : 'hover:bg-indigo-800/50 text-indigo-200 hover:text-white hover:translate-x-1' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m8-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">AI Symptom Checker</span>
                </a>
            </li>

            <li>
                <a href="{{ route('patient.messages.index') }}" 
                   class="flex items-center gap-3 p-3.5 rounded-xl transition-all duration-200 group {{ request()->is('patient/messages*') || request()->is('patient/chat*') ? 'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white shadow-lg shadow-violet-500/25' : 'hover:bg-indigo-800/50 text-indigo-200 hover:text-white hover:translate-x-1' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8 4.03-8 9-8 9 3.582 9 8z"/></svg>
                    <span class="font-medium">Messages</span>
                </a>
            </li>

            <li>
                <a href="{{ route('patient.medical-history.index') }}"
                   class="flex items-center gap-3 p-3.5 rounded-xl transition-all duration-200 group {{ request()->is('patient/medical-history*') ? 'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white shadow-lg shadow-violet-500/25' : 'hover:bg-indigo-800/50 text-indigo-200 hover:text-white hover:translate-x-1' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h6m-6 8h6m2 4H7a2 2 0 01-2-2V7a2 2 0 012-2h5.586A1 1 0 0113.293 5.293l5.414 5.414A1 1 0 0119 11.414V19a2 2 0 01-2 2z"/></svg>
                    <span class="font-medium">Medical History</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="p-4 border-t border-indigo-700/50">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-indigo-800/50 hover:bg-red-500/20 hover:text-red-300 text-indigo-200 py-3 rounded-xl font-medium transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Log Out
            </button>
        </form>
    </div>
</aside>
