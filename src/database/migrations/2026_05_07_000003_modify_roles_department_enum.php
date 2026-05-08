<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE roles MODIFY COLUMN department ENUM('HR', 'IT', 'Finance', 'Operations', 'Marketing', 'Sales', 'Admin', 'Support') NOT NULL");
        } elseif ($driver === 'sqlite') {
            Schema::table('roles', function (Blueprint $table) {
                $table->string('department', 50)->change();
            });
        }
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE roles MODIFY COLUMN department VARCHAR(255) NOT NULL");
        } elseif ($driver === 'sqlite') {
            Schema::table('roles', function (Blueprint $table) {
                $table->string('department', 255)->change();
            });
        }
    }
};
