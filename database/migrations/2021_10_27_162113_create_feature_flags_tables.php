<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeatureFlagsTables extends Migration
{
    public function up()
    {
        Schema::create('feature_flags', function (Blueprint $table) {
            // this will create an id, a "published" column, and soft delete and timestamps columns
            createDefaultTableFields($table);

            $table->string('title', 200)->nullable();

            $table->string('code', 200)->nullable();

            $table->boolean('publicly_available')->default(false);

            $table->text('description')->nullable();
        });

        Schema::create('feature_flag_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'feature_flag');
        });
    }

    public function down()
    {
        Schema::dropIfExists('feature_flag_revisions');
        Schema::dropIfExists('feature_flags');
    }
}
