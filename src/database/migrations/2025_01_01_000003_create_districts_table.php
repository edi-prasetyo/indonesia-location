<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('province_id')
                ->constrained('provinces')
                ->cascadeOnDelete();

            $table->foreignId('regency_id')
                ->constrained('regencies')
                ->cascadeOnDelete();

            $table->string('code', 15)->unique();
            $table->string('province_code', 10)->index();
            $table->string('regency_code', 10)->index();

            $table->string('name');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
