<?php

use App\Models\Appointment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('appointments')
            ->select(['id', 'status'])
            ->orderBy('id')
            ->chunkById(100, function ($appointments): void {
                foreach ($appointments as $appointment) {
                    $normalizedStatus = (string) Appointment::normalizeStatus($appointment->status);

                    if ((string) $appointment->status === $normalizedStatus) {
                        continue;
                    }

                    DB::table('appointments')
                        ->where('id', $appointment->id)
                        ->update(['status' => $normalizedStatus]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('appointments')
            ->where('status', (string) Appointment::STATUS_PENDING)
            ->update(['status' => 'scheduled']);
    }
};
