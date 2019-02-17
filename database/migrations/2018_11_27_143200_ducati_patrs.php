<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DucatiPatrs extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('ducati_parts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('id_diagrams')->index();
            $table->string('code', 25);
            $table->text('desctiption');
            $table->integer('min_qty');
            $table->unsignedDecimal('price_netto', 8, 2)->default(0);
            $table->unsignedDecimal('price_gross', 8, 2)->default(0);
            $table->timestamps();
        });
        Schema::table('ducati_parts', function (Blueprint $table) {
            $table->foreign('id_diagrams')->references('id')->on('ducati_diagrams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('ducati_parts');
    }

}
