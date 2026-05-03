<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_status', 32)->default('pending')->after('status');
            $table->timestamp('payment_paid_at')->nullable()->after('payment_status');
            $table->string('xendit_reference')->nullable()->after('payment_paid_at');
        });

        // Data lama: anggap sudah lunas (perilaku checkout sebelumnya)
        foreach (DB::table('orders')->where('status', 'paid')->get() as $row) {
            DB::table('orders')->where('id', $row->id)->update([
                'payment_status' => 'paid',
                'payment_paid_at' => $row->updated_at ?? $row->created_at,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_paid_at', 'xendit_reference']);
        });
    }
};
