<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DucatiModels extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('ducati_models', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('id_machines')->index();
            $table->unsignedInteger('id_type_machines');
            $table->text('name');
            $table->unsignedInteger('year');
            $table->timestamps();
        });
        Schema::table('ducati_models', function (Blueprint $table) {
            $table->foreign('id_machines')->references('id')->on('ducati_machines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('ducati_models');
    }

}
