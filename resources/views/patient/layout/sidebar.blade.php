<aside class="w-64 bg-[#1e293b] text-white flex flex-col fixed h-full">
            <div class="p-6 flex items-center justify-center border-b border-gray-700">
                <a href="{{ route('patient.dashboard.index') }}" class="hover:opacity-80 transition-opacity">
                    <h1 class="text-2xl font-black tracking-tighter uppercase">
                        <span class="text-white">MyHealth</span><span class="text-orange-500">Matters</span>
                    </h1>
                    <p class="text-[10px] text-gray-400 text-center tracking-[0.2em] -mt-1 font-bold">.FIT</p>
                </a>
            </div>

            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li><a href="{{ route('patient.dashboard.index') }}" class="flex items-center gap-3 p-3 rounded hover:bg-gray-700 @if(request()->is('patient/dashboard')) bg-gray-700 @endif"><i class="icon">🏠</i> Dashboard</a></li>
                    <li><a href="{{ route('patient.doctors.index') }}" class="flex items-center gap-3 p-3 rounded hover:bg-gray-700 @if(request()->is('patient/doctors')) bg-gray-700 @endif"><i class="icon">🔍</i> Find Doctors</a></li>
                    <li><a href="{{ route('patient.appointments.index') }}" class="flex items-center gap-3 p-3 rounded hover:bg-gray-700 @if(request()->is('patient/appointments')) bg-gray-700 @endif"><i class="icon">📅</i> My Appointments</a></li>
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