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
        Schema::create('taches', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->boolean('etat_fait');
            $table->integer('ordre');
            $table->timestamps();
            //$table->dropForeign(['to_do_list_id']);
            $table->foreignId('to_do_list_id')->constrained('to_do_lists')->onDelete('cascade')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taches');
    }
};
