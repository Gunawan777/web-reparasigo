<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyRoleColumnInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Using raw SQL to avoid doctrine/dbal issues and ensure compatibility.
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // It's acceptable to have a less specific down migration for a simple change.
        // The original state is unknown, so we can't perfectly revert.
        // We'll assume reverting to a smaller varchar is sufficient if needed, but for now, we can leave it.
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(20) NOT NULL");
    }
}