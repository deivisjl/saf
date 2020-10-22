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
                        <h3 class="card-title">Cobrar factura No. {{ $factura->venta->id }}</h3>
                    </div>
                    <div class="card-body">
                        <pago-cliente-component :factura="{{ $factura }}"></pago-cliente-component>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
