<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatogeryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catogery_translations', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedBigInteger('catogery_id');
            $table->string('locale')->index();
            $table->string('name');

            $table->text('description');
            $table->unique(['catogery_id','locale']);
            $table->foreign('catogery_id')->references('id')->on('catogeries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catogery_translations');
    }
}
