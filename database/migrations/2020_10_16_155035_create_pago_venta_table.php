<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagoVentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pago_venta', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('factura_venta_id')->unsigned();
            $table->decimal('monto');
            $table->timestamps();
            $table->foreign('factura_venta_id')->references('id')->on('factura_venta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pago_venta');
    }
}
