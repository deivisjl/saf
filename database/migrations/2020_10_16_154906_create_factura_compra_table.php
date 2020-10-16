<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturaCompraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factura_compra', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('numero');
            $table->decimal('monto',8,2);
            $table->decimal('saldo',8,2);
            $table->bigInteger('compra_id')->unsigned();
            $table->bigInteger('estado_id')->unsigned();
            $table->timestamps();
            $table->foreign('compra_id')->references('id')->on('compra');
            $table->foreign('estado_id')->references('id')->on('estado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('factura_compra');
    }
}
