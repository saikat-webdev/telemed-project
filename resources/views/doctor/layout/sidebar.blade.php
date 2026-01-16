<aside class="w-64 bg-[#1e293b] text-white flex flex-col fixed h-full shadow-xl">
    <div class="p-6 flex flex-col items-center justify-center border-b border-gray-700/50">
        <a href="{{ route('doctor.dashboard') }}" class="hover:opacity-80 transition-opacity text-center">
            <h1 class="text-2xl font-black tracking-tighter uppercase leading-none">
                <span class="text-white">MyHealth</span><span class="text-orange-500">Matters</span>
            </h1>
            <div class="mt-2 inline-block bg-orange-500/10 border border-orange-500/20 px-3 py-1 rounded-full">
                <p class="text-[10px] text-orange-400 tracking-[0.2em] font-black uppercase">Doctor Portal</p>
            </div>
        </a>
    </div>

    <nav class="flex-1 p-4 overflow-y-auto">
        <ul class="space-y-1">
            <li>
                <a href="{{ route('doctor.dashboard') }}" 
                   class="flex items-center gap-3 p-3 rounded-lg transition-colors {{ request()->is('doctor/dashboard') ? 'bg-orange-500 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="" 
                   class="flex items-center gap-3 p-3 rounded-lg transition-colors {{ request()->is('doctor/appointments') ? 'bg-orange-500 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="font-medium">My Appointments</span>
                </a>
            </li>

            <li>
                <a href="#" class="flex items-center gap-3 p-3 rounded-lg transition-colors text-gray-300 hover:bg-gray-700/50 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="font-medium">Medical Records</span>
                </a>
            </li>

            <li>
                <a href="#" class="flex items-center gap-3 p-3 rounded-lg transition-colors text-gray-300 hover:bg-gray-700/50 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="font-medium">Settings</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="p-4 border-t border-gray-700/50">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-white text-[#1e293b] py-2.5 rounded-lg font-bold hover:bg-orange-500 hover:text-white transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Log Out
            </button>
        </form>
    </div>
</aside>