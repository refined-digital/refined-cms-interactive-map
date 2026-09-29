<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // the existing content column is reused for the marker's copy
        Schema::table('maps', function (Blueprint $table) {
            $table->string('eyebrow')->nullable()->after('name');
            $table->string('heading')->nullable()->after('eyebrow');
        });
    }

    public function down(): void
    {
        Schema::table('maps', function (Blueprint $table) {
            $table->dropColumn(['eyebrow', 'heading']);
        });
    }
};
