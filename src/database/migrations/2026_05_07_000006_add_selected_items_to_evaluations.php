<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->json('selected_competencies')->nullable()->after('period');
            $table->json('selected_indicators')->nullable()->after('selected_competencies');
        });
    }

    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropColumn(['selected_competencies', 'selected_indicators']);
        });
    }
};
