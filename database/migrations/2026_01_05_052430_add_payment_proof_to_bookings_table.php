<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddPaymentProofToBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_proof')->nullable()->after('payment_status');
        });

        // Using raw SQL to modify ENUM to avoid doctrine/dbal dependency issues
        DB::statement("ALTER TABLE bookings CHANGE COLUMN payment_status payment_status ENUM('pending', 'verifying', 'paid', 'refunded') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('payment_proof');
        });

        // Revert ENUM modification
        DB::statement("ALTER TABLE bookings CHANGE COLUMN payment_status payment_status ENUM('pending', 'paid', 'refunded') NOT NULL DEFAULT 'pending'");
    }
}
