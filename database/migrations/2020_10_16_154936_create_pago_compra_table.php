<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagoCompraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pago_compra', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('factura_compra_id')->unsigned();
            $table->decimal('monto');
            $table->timestamps();
            $table->foreign('factura_compra_id')->references('id')->on('factura_compra');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pago_compra');
    }
}
