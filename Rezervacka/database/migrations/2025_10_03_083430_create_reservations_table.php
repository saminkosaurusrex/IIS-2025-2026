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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string("access_code")->unique();;
            $table->foreignId("event_id")->constrained()->onDelete('cascade');
            $table->foreignId("user_id")->nullable()->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->integer('row');
            $table->integer('column');
            $table->timestamp("reserved_at");
            $table->timestamp("confirmed_at")->nullable();
            $table->timestamp("paid_at")->nullable();
            $table->timestamp("canceled_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
