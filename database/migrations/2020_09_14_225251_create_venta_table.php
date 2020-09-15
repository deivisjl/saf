<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venta', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cliente_id')->unsigned();
            $table->bigInteger('factura_no');
            $table->string('serie');
            $table->decimal('monto',6,2);
            $table->bigInteger('estado_id')->unsigned();
            $table->bigInteger('forma_pago_id')->unsigned();
            $table->foreign('cliente_id')->references('id')->on('cliente');
            $table->foreign('estado_id')->references('id')->on('estado');
            $table->foreign('forma_pago_id')->references('id')->on('forma_pago');
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
        Schema::dropIfExists('venta');
    }
}
