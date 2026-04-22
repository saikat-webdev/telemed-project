<header class="bg-white/80 backdrop-blur-md border-b border-indigo-100">
    <div class="flex items-center justify-between px-6 py-4">
        <div class="flex items-center gap-4">
            <h2 class="text-2xl font-bold text-indigo-950">@yield('page-title', 'Dashboard')</h2>
        </div>
        <div class="flex items-center gap-4">
            <div class="relative">
                <button class="p-2.5 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-xl transition-all duration-200">
                    <i class="fas fa-bell text-lg"></i>
                </button>
            </div>
            <div class="flex items-center gap-3 pl-4 border-l border-indigo-100">
                <div class="w-11 h-11 bg-gradient-to-br from-violet-500 to-fuchsia-600 rounded-xl flex items-center justify-center text-white font-bold shadow-lg shadow-violet-500/25">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="hidden md:block">
                    <p class="text-sm font-semibold text-indigo-950">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-indigo-500">Administrator</p>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="h-6"></div>
