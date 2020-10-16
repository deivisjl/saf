<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compra', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('factura_no');
            $table->decimal('monto',6,2);
            $table->date('fecha_emision');
            $table->bigInteger('forma_pago_id')->unsigned();
            $table->bigInteger('proveedor_id')->unsigned();
            $table->foreign('forma_pago_id')->references('id')->on('forma_pago');
            $table->foreign('proveedor_id')->references('id')->on('proveedor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compra');
    }
}
