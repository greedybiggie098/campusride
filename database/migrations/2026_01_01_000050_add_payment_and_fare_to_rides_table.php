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
        Schema::table('rides', function (Blueprint $table) {
            $table->decimal('fare', 8, 2)->default(2.50)->after('status');
            $table->enum('payment_method', ['campus_card', 'student_wallet', 'cash', 'online'])->default('campus_card')->after('fare');
            $table->enum('payment_status', ['unpaid', 'paid', 'waived'])->default('unpaid')->after('payment_method');
            $table->dateTime('paid_at')->nullable()->after('payment_status');
            $table->boolean('is_priority')->default(false)->after('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->dropColumn(['fare', 'payment_method', 'payment_status', 'paid_at', 'is_priority']);
        });
    }
};
