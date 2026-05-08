<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->decimal('competency_score', 4, 2)->nullable()->after('total_score');
            $table->decimal('indicator_score', 4, 2)->nullable()->after('competency_score');
        });
    }

    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropColumn(['competency_score', 'indicator_score']);
        });
    }
};
