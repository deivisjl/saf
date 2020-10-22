@extends('layouts.app')

@section('content')
<div id="loading"></div>
<!-- /.content-header -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Abonar factura No. {{ $factura->compra->factura_no }}</h3>
                    </div>
                    <div class="card-body">
                        <pago-proveedor-component :factura="{{ $factura }}"></pago-proveedor-component>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
