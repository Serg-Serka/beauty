<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('working_days');
            $table->string('working_hours');// interval is 30mins
            $table->boolean('is_enabled');
            $table->unsignedBigInteger('salon_id');
            $table->unsignedBigInteger('service_type_id');

            $table->foreign('salon_id')->references('id')->on('salons');
            $table->foreign('service_type_id')->references('id')->on('service_types');

            $table->comment('Services');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
