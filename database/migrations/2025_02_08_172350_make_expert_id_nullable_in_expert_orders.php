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
        Schema::table('expert_orders', function (Blueprint $table) {
            $table->foreignId('expert_id')->nullable()->change();
            $table->time('time')->nullable()->change();
            $table->date('date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expert_orders', function (Blueprint $table) {
            //
        });
    }
};
