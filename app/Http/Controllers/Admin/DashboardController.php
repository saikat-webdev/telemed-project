<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\DoctorCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard with analytics.
     */
    public function index()
    {
        // Get counts for dashboard cards
        $stats = [
            'totalUsers' => User::whereHas('roles', function ($query) {
                $query->where('name', 'patient');
            })->count(),
            'totalDoctors' => Doctor::count(),
            'totalAppointments' => Appointment::count(),
            'pendingAppointments' => Appointment::where('status', 0)->count(),
            'completedAppointments' => Appointment::where('status', 3)->count(),
            'todayAppointments' => Appointment::whereDate('appointment_date', now()->toDateString())->count(),
            'totalCategories' => DoctorCategory::count(),
        ];

        // Get recent appointments
        $recentAppointments = Appointment::with(['doctor', 'patient'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get monthly appointment data for chart
        $monthlyAppointments = Appointment::selectRaw('MONTH(appointment_date) as month, COUNT(*) as count')
            ->whereYear('appointment_date', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month');

        // Get status distribution
        $statusDistribution = Appointment::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Get recent doctors
        $recentDoctors = Doctor::with('category')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get recent patients
        $recentPatients = User::whereHas('roles', function ($query) {
                $query->where('name', 'patient');
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'recentAppointments',
            'monthlyAppointments',
            'statusDistribution',
            'recentDoctors',
            'recentPatients'
        ));
    }

    /**
     * Display all doctors management page.
     */
    public function doctors()
    {
        $doctors = Doctor::with(['category', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.doctors.index', compact('doctors'));
    }

    /**
     * Display doctor details.
     */
    public function doctorShow($id)
    {
        $doctor = Doctor::with(['category', 'user', 'appointments.patient'])
            ->findOrFail($id);

        $doctorAppointments = Appointment::where('doctor_id', $id)
            ->with('patient')
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        return view('admin.doctors.show', compact('doctor', 'doctorAppointments'));
    }

    /**
     * Display all patients management page.
     */
    public function patients()
    {
        $patients = User::whereHas('roles', function ($query) {
                $query->where('name', 'patient');
            })
            ->with('appointments')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.patients.index', compact('patients'));
    }

    /**
     * Display patient details.
     */
    public function patientShow($id)
    {
        $patient = User::with(['roles', 'appointments.doctor'])
            ->findOrFail($id);

        $patientAppointments = Appointment::where('patient_id', $id)
            ->with('doctor')
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        return view('admin.patients.show', compact('patient', 'patientAppointments'));
    }

    /**
     * Display all appointments management page.
     */
    public function appointments()
    {
        $appointments = Appointment::with(['doctor', 'patient'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(20);

        return view('admin.appointments.index', compact('appointments'));
    }

    /**
     * Display appointment details.
     */
    public function appointmentShow($id)
    {
        $appointment = Appointment::with(['doctor', 'patient', 'review'])
            ->findOrFail($id);

        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Update appointment status.
     */
    public function updateAppointmentStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = $request->status;
        $appointment->save();

        return redirect()->back()->with('success', 'Appointment status updated successfully.');
    }

    /**
     * Display categories management page.
     */
    public function categories()
    {
        $categories = DoctorCategory::all();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Display analytics page.
     */
    public function analytics()
    {
        // Monthly appointments for the current year
        $monthlyAppointments = Appointment::selectRaw('MONTH(appointment_date) as month, COUNT(*) as count')
            ->whereYear('appointment_date', now()->year)
            ->groupBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->month => $item->count];
            });

        // Top doctors by appointments
        $topDoctors = Doctor::with('category')
            ->take(10)
            ->get();

        // Appointments by status
        $statusCounts = Appointment::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Revenue stats (based on completed appointments with fees)
        $totalRevenue = Appointment::where('status', 3)->count() * 100; // Assuming $100 per consultation
        $monthlyRevenue = Appointment::where('status', 3)
            ->whereMonth('appointment_date', now()->month)
            ->count() * 100;

        return view('admin.analytics.index', compact(
            'monthlyAppointments',
            'topDoctors',
            'statusCounts',
            'totalRevenue',
            'monthlyRevenue'
        ));
    }

    // ===== DOCTOR CRUD METHODS =====
    public function doctorCreate()
    {
        $categories = DoctorCategory::all();
        return view('admin.doctors.create', compact('categories'));
    }

    public function doctorStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors',
            'phone' => 'required|string',
            'specialization' => 'required|exists:doctor_categories,id',
            'fees' => 'required|numeric|min:0',
        ]);

        Doctor::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'specialization' => $request->specialization,
            'fees' => $request->fees,
        ]);

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor created successfully.');
    }

    public function doctorEdit($id)
    {
        $doctor = Doctor::findOrFail($id);
        $categories = DoctorCategory::all();
        return view('admin.doctors.edit', compact('doctor', 'categories'));
    }

    public function doctorUpdate(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email,' . $id,
            'phone' => 'required|string',
            'specialization' => 'required|exists:doctor_categories,id',
            'fees' => 'required|numeric|min:0',
        ]);

        $doctor->update($request->all());

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor updated successfully.');
    }

    public function doctorDestroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor deleted successfully.');
    }

    // ===== CATEGORY CRUD METHODS =====
    public function categoryCreate()
    {
        return view('admin.categories.create');
    }

    public function categoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:doctor_categories,name',
            'description' => 'nullable|string',
        ]);

        DoctorCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function categoryEdit($id)
    {
        $category = DoctorCategory::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function categoryUpdate(Request $request, $id)
    {
        $category = DoctorCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:doctor_categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function categoryDestroy($id)
    {
        $category = DoctorCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    // ===== PATIENT MANAGEMENT =====
    public function patientDestroy($id)
    {
        $patient = User::findOrFail($id);
        
        // Remove patient role and make inactive instead of deleting
        $patient->removeRole('patient');
        $patient->delete();

        return redirect()->route('admin.patients.index')->with('success', 'Patient removed successfully.');
    }
}
