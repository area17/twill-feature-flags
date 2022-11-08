<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeatureFlagsTables extends Migration
{
    public function up(): void
    {
        Schema::create('feature_flags', function (Blueprint $table) {
            // this will create an id, a "published" column, and soft delete and timestamps columns
            createDefaultTableFields($table);

            $table->string('title', 200)->nullable();

            $table->string('code', 200)->nullable();

            $table->boolean('publicly_available')->default(false);

            $table->text('description')->nullable();

            $table->text('ip_addresses')->nullable();
        });

        Schema::create('feature_flag_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'feature_flag');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_flag_revisions');
        Schema::dropIfExists('feature_flags');
    }
}
