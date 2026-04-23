<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $needsTransactionId = ! Schema::hasColumn('appointments', 'transaction_id');

        Schema::table('appointments', function (Blueprint $table) use ($needsTransactionId) {
            if ($needsTransactionId) {
                $table->unsignedBigInteger('transaction_id')->nullable();
            }

            $table->date('original_appointment_date')->nullable();
            $table->time('original_appointment_time')->nullable();
            $table->timestamp('rescheduled_at')->nullable();
            $table->timestamp('reminder_sent_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $dropColumns = [
            'original_appointment_date',
            'original_appointment_time',
            'rescheduled_at',
            'reminder_sent_at',
        ];

        if (Schema::hasColumn('appointments', 'transaction_id')) {
            $dropColumns[] = 'transaction_id';
        }

        Schema::table('appointments', function (Blueprint $table) use ($dropColumns) {
            $table->dropColumn($dropColumns);
        });
    }
};
