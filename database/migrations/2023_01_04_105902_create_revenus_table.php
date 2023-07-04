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
        Schema::create('revenus', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->double('montant');
            $table->string('nature');
            $table->string('frequence');
            $table->dateTime('date_in');
            $table->string('source');
            $table->timestamps();
            //$table->dropForeign(['user_id']);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->change();
            // to drop foreign key : $table->dropForeign(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revenus');
    }
};
