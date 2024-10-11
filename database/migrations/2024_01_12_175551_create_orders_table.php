<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('phone');
            $table->boolean('verified')->default(false);
            $table->string('status', 1);
            $table->string('shipping_type');
            $table->string('delivery_area')->nullable();
            $table->string('delivery_address');
            $table->boolean('is_preorder')->default(false);
            $table->string('preorder_datetime')->nullable();
            $table->text('note')->nullable();
            $table->integer('number_persons')->default(1);
            $table->string('payment_method');
            $table->unsignedBigInteger('promotion_id')->nullable();
            $table->integer('original_price')->nullable();
            $table->integer('result_price')->nullable();
            $table->integer('bonus_points')->nullable();
            $table->integer('condition_id')->nullable();
            $table->json('condition_data')->nullable();
            $table->integer('request_send')->default(1);
            $table->integer('receipt_code');

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('orders');
        Schema::enableForeignKeyConstraints();
    }
};
