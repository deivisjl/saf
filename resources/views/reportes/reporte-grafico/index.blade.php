@extends('layouts.app')

@section('content')
<div id="loading"></div>
<!-- /.content-header -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                {{--  --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Compras de producto</h3>
                            </div>
                            <div class="card-body">
                                <grafico-compra-categoria></grafico-compra-categoria>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Ventas de producto</h3>
                            </div>
                            <div class="card-body">
                                <grafico-venta-categoria></grafico-venta-categoria>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Categorías más vendidas</h3>
                            </div>
                            <div class="card-body">
                                <grafico-producto-vendido></grafico-producto-vendido>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Existencias en inventario</h3>
                            </div>
                            <div class="card-body">
                                <grafico-existencia-inventario></grafico-existencia-inventario>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Ventas por mes <small>(Cifras en quetzales)</small></h3>
                            </div>
                            <div class="card-body">
                                <grafico-venta-mes></grafico-venta-mes>
                            </div>
                        </div>
                    </div>
                </div>
                {{--  --}}
            </div>
        </div>
    </div>
</section>
@endsection

