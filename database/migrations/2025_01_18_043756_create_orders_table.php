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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->unsignedBigInteger('category_package_id');
            $table->foreign('category_package_id')->references('id')->on('category_packages')->onDelete('cascade');

            $table->string('area');
            $table->string('house_no');
            $table->string('road_no');
            $table->string('block');
            $table->string('district');
            $table->string('additional_info');

            $table->double('order_amount');
            $table->double('discount');
            $table->string('cupon');
            $table->double('cupon_amount');
            $table->string('description');
            $table->date('date');
            $table->string('slot');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
