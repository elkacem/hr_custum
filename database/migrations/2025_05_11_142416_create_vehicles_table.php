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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->integer('brand_id')->default(0);
            $table->integer('seater_id')->default(0);
            $table->decimal('price', 28, 8)->default(0);
            $table->text('details')->nullable();
            $table->text('images')->nullable();
            $table->string('model', 40)->nullable();
            $table->integer('doors')->default(0);
            $table->string('transmission', 40)->nullable();
            $table->string('fuel_type', 40)->nullable();
            $table->text('specifications')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
