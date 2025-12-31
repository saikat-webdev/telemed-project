<aside class="w-64 bg-[#1e293b] text-white flex flex-col fixed h-full">
            <div class="p-6 flex items-center gap-2 border-b border-gray-700">
                <div class="w-8 h-8 bg-white text-[#1e293b] rounded flex items-center justify-center font-bold">T</div>
                <h1 class="text-xl font-bold">TeleHealth<span class="text-orange-500">)</span></h1>
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