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
            DB::statement("ALTER TABLE positions MODIFY COLUMN level ENUM('Intern', 'Junior', 'Mid-Level', 'Senior', 'Lead', 'Manager', 'Director', 'VP', 'Executive') NOT NULL");
        } elseif ($driver === 'sqlite') {
            Schema::table('positions', function (Blueprint $table) {
                $table->string('level', 50)->change();
            });
        }
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE positions MODIFY COLUMN level VARCHAR(50) NOT NULL");
        } elseif ($driver === 'sqlite') {
            Schema::table('positions', function (Blueprint $table) {
                $table->string('level', 50)->change();
            });
        }
    }
};
