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
        Schema::create('dry_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('invoice_number')->unique();
            $table->string('address');
            $table->string('pic_spot');
            $table->string('instructions')->nullable();
            $table->text('instructions_text')->nullable();
            $table->string('delivery_speed_type');
            $table->string('detergent_type');
            $table->boolean('is_delicate_cycle')->default(false);
            $table->boolean('is_hang_dry')->default(false);
            $table->boolean('is_return_hanger')->default(false);
            $table->boolean('is_additional_request')->default(false);
            $table->string('coverage_type')->nullable();
            $table->date('order_date')->default(now());
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['Pending', 'Order Accepted','In process','Wait for deliver', 'Completed', 'Canceled'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dry_orders');
    }
};
