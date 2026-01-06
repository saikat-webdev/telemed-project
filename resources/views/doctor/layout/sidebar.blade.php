<aside class="w-64 bg-[#1e293b] text-white flex flex-col fixed h-full">
            <div class="p-6 flex items-center gap-3 border-b border-gray-700">
                <div class="w-8 h-8 bg-white text-[#1e293b] rounded flex items-center justify-center font-bold shadow-lg">T</div>
                <div class="flex flex-col">
                    <h1 class="text-xl font-bold text-white leading-none">TeleHealth<span class="text-orange-500">.</span></h1>
                    <span class="mt-1 text-[10px] uppercase tracking-widest font-extrabold text-orange-400 bg-orange-500/10 px-2 py-0.5 rounded-full border border-orange-500/20 w-fit">
                        For Doctors
                    </span>
                </div>
            </div>

            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li><a href="{{ route('doctor.dashboard') }}" class="flex items-center gap-3 p-3 rounded hover:bg-gray-700 @if(request()->is('doctor/dashboard')) bg-gray-700 @endif"><i class="icon">🏠</i> Dashboard</a></li>
                    <li><a href="" class="flex items-center gap-3 p-3 rounded hover:bg-gray-700 @if(request()->is('doctor/appointments')) bg-gray-700 @endif"><i class="icon">📅</i> My Appointments</a></li>
                    <li><a href="#" class="flex items-center gap-3 p-3 rounded hover:bg-gray-700"><i class="icon">📄</i> Medical Records</a></li>
                    <li><a href="#" class="flex items-center gap-3 p-3 rounded hover:bg-gray-700"><i class="icon">⚙️</i> Settings</a></li>
                </ul>
            </nav>

            <div class="p-6 border-t border-gray-700">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-white text-black py-2 rounded font-medium hover:bg-gray-200">Log Out</button>
                </form>
            </div>
        </aside>