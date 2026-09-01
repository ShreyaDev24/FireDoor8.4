<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bom_calculations', function (Blueprint $table) {
            if (!Schema::hasColumn('bom_calculations', 'Breakdown')) {
                $table->longText('Breakdown')->nullable()->after('Description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bom_calculations', function (Blueprint $table) {
            if (Schema::hasColumn('bom_calculations', 'Breakdown')) {
                $table->dropColumn('Breakdown');
            }
        });
    }
};
