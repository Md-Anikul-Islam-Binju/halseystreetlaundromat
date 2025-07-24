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
        Schema::create('dry_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dry_order_id');
            $table->unsignedBigInteger('service_id');
            $table->integer('is_crease')->default(0);
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->foreign('dry_order_id')->references('id')->on('dry_orders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dry_order_items');
    }
};
