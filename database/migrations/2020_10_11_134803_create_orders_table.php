<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('status')->nullable();
            $table->string('date')->nullable();
            $table->string('number')->nullable();
            $table->string('total')->nullable();
            $table->integer('capon_id')->nullable();
            $table->integer('user_id')->nullable();

            $table->string('address_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('copan')->nullable();
            $table->integer('payment_id')->nullable();

//            $table->string('lang_code')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->userstamps();
            $table->softUserstamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
