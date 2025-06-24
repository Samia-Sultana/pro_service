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
        Schema::create('expert_incomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->decimal('income_amount', 10, 2);
            $table->string('remarks')->nullable();
            $table->string('attachment')->nullable();
            $table->date('date')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('expert_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('expert_incomes');
    }
};
