@extends('layouts.app')

@section('content')
<div id="loading"></div>
<!-- /.content-header -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Venta de productos</h3>
                    </div>
                    <div class="card-body">
                        <venta-component :formas_pago="{{ $formaPago }}" :clientes="{{ $clientes }}" :productos="{{ $productos }}"></venta-component>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
