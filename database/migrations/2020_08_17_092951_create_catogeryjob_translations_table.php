<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatogeryjobTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catogeryjob_translations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('catogeryjob_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->string('description');
            $table->unique(['catogeryjob_id','locale']);
            $table->foreign('catogeryjob_id')->references('id')->on('catogeryjobs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catogeryjob_translations');
    }
}
