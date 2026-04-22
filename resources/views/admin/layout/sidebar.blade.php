<aside class="w-64 bg-gradient-to-b from-indigo-950 via-indigo-900 to-violet-950 text-white flex flex-col fixed h-full shadow-2xl z-50">
    <div class="p-6 flex items-center justify-center border-b border-indigo-700/50">
        <a href="{{ route('admin.dashboard') }}" class="hover:opacity-90 transition-all duration-300">
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
            <p class="text-[10px] text-indigo-300 text-center tracking-[0.25em] mt-2 font-medium">ADMIN PORTAL</p>
        </a>
    </div>

    <nav class="flex-1 p-4 overflow-y-auto">
        <ul class="space-y-1">
            <li>
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 p-3.5 rounded-xl transition-all duration-200 group {{ request()->is('admin/dashboard') ? 'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white shadow-lg shadow-violet-500/25' : 'hover:bg-indigo-800/50 text-indigo-200 hover:text-white hover:translate-x-1' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.doctors.index') }}" 
                   class="flex items-center gap-3 p-3.5 rounded-xl transition-all duration-200 group {{ request()->is('admin/doctors*') ? 'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white shadow-lg shadow-violet-500/25' : 'hover:bg-indigo-800/50 text-indigo-200 hover:text-white hover:translate-x-1' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">Manage Doctors</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.patients.index') }}" 
                   class="flex items-center gap-3 p-3.5 rounded-xl transition-all duration-200 group {{ request()->is('admin/patients*') ? 'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white shadow-lg shadow-violet-500/25' : 'hover:bg-indigo-800/50 text-indigo-200 hover:text-white hover:translate-x-1' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span class="font-medium">Manage Patients</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.appointments.index') }}" 
                   class="flex items-center gap-3 p-3.5 rounded-xl transition-all duration-200 group {{ request()->is('admin/appointments*') ? 'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white shadow-lg shadow-violet-500/25' : 'hover:bg-indigo-800/50 text-indigo-200 hover:text-white hover:translate-x-1' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="font-medium">Appointments</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center gap-3 p-3.5 rounded-xl transition-all duration-200 group {{ request()->is('admin/categories*') ? 'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white shadow-lg shadow-violet-500/25' : 'hover:bg-indigo-800/50 text-indigo-200 hover:text-white hover:translate-x-1' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span class="font-medium">Categories</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.analytics.index') }}" 
                   class="flex items-center gap-3 p-3.5 rounded-xl transition-all duration-200 group {{ request()->is('admin/analytics*') ? 'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white shadow-lg shadow-violet-500/25' : 'hover:bg-indigo-800/50 text-indigo-200 hover:text-white hover:translate-x-1' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span class="font-medium">Analytics</span>
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