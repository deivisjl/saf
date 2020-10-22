@extends('layouts.app')

@section('content')
<div id="loading"></div>
<!-- /.content-header -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detalle del registro</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table style="width: 100%">
                                    <tbody>
                                        <tr>
                                            <th>No. de factura</th>
                                            <td>{{ $registro->venta->id }}</td>
                                            <th>Fecha de emisión</th>
                                            <td>{{ \Carbon\Carbon::parse($registro->venta->created_at)->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Monto de venta</th>
                                            <td>Q. {{ $registro->monto }}</td>
                                            <th>Saldo pendiente</th>
                                            <td>Q. {{ $registro->saldo }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-default btn-block btn-lg">Detalle de pagos</button>
                            </div>
                            @if (sizeof($registro->pago_venta) > 0)
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Monto</th>
                                                <th>Fecha de pago</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                            
                                            @foreach ($registro['pago_venta'] as $key => $item)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>Q. {{ $item->monto }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item['created_at'])->format('d/m/Y') }}</td>
                                                </tr>    
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="col-md-12 text-center">
                                    <span>No se ha realizado ningún pago..!</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <a class="btn btn-default" href="{{ route('pago-clientes.index') }}">Regresar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
