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
        Schema::create('plan_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->default(0);
            $table->integer('plan_id')->default(0);
            $table->integer('pick_location')->default(0);
            $table->timestamp('pick_time')->nullable();
            $table->timestamp('drop_time')->nullable();
            $table->decimal('price', 28, 8)->default(0);
            $table->string('trx', 40)->nullable();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_logs');
    }
};
