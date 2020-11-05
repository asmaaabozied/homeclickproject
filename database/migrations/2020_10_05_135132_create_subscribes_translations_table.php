<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribeTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribe_translations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('subscribe_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->string('description');
            $table->unique(['subscribe_id','locale']);
            $table->foreign('subscribe_id')->references('id')->on('subscribes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscribes_translations');
    }
}
