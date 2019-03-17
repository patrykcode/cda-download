<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DucatiDiagrams extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('ducati_diagrams', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('id_models')->index();
            $table->unsignedInteger('id_machines');
            $table->text('image')->nullable();
            $table->string('type_part', 25)->nullable();
            $table->timestamps();
        });
        Schema::table('ducati_diagrams', function (Blueprint $table) {
            $table->foreign('id_models')->references('id')->on('ducati_models')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('ducati_diagrams');
    }

}
