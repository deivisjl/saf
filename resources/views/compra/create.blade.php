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
                        <h3 class="card-title">Ingreso de productos</h3>
                    </div>
                    <div class="card-body">
                        <compra-component :estados="{{ $estados }}" :formas_pago="{{ $formas }}" :proveedores="{{ $proveedores }}" :productos="{{ $productos }}"></compra-component>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
