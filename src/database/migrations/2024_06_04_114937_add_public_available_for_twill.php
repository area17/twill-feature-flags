<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('twill_feature_flags', function (Blueprint $table) {
            $table->boolean('publicly_available_twill_users')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('twill_feature_flags', function (Blueprint $table) {
            $table->dropColumn('publicly_available_twill_users');
        });
    }
};
