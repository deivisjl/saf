<!DOCTYPE html>
<html lang="es">	
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Comprobante de venta</title>
	<style type="text/css">
			.table {
				    width: 100%;
				    max-width: 100%;
				    margin-bottom: 20px;
			}
			.table-bordered {
			    border: 1px solid #337ab7;
			}
			table {
				    background-color: transparent;
				}
			table {
			    border-spacing: 0;
			    border-collapse: collapse;
			}
			.table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {
				    border: 1px solid #337ab7;
				}
			.table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
			    padding: 8px;
			    line-height: 1.42857143;
			    vertical-align: top;
			    border-top: 1px solid #337ab7;
			}
			th {
			    text-align: left;
			}
			td, th {
			    padding: 0;
            }
            .panel {
                margin-bottom: 20px;
                background-color: #fff;
                border: 1px solid transparent;
                border-radius: 4px;
                -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
                box-shadow: 0 1px 1px rgba(0,0,0,.05);
            }
			.panel-herbalife {
			    border-color: #337ab7;
            }
            .panel-body {
                padding: 15px;
            }
            .text-center{
                text-align: center;
            }
			.custom-table {
				    background-color: #337ab7 !important;
				    color: #fff !important;
				}
			.body {
			    font-family: DejaVu Sans;
            }
            .col-xs-12{
                width: 100%
            }
	</style>
</head>
<body>
	<div class="row"  style="margin-bottom: 0px !important; line-height: 1;">
		<div class="col-xs-12 text-center">
            <img src="{{ asset('images/funerarialogo.png') }}" alt="funeraria" height="60px">
		</div>
	</div>
	<table width="100%">
		<tr>
			<th width="70%">Comprobante No. {{ $venta->id }}</th>
			<th width="10%" class="text-center table-bordered" style="border: 1px solid #337ab7;background-color: #337ab7; color:#fff;">DIA</th>
			<th width="10%" class="text-center table-bordered" style="border: 1px solid #337ab7; background-color: #337ab7; color:#fff;">MES</th>
			<th width="10%" class="text-center table-bordered" style="border: 1px solid #337ab7; background-color: #337ab7; color:#fff;">AÑO</th>
		</tr>
		<tr>
			<td width="70%"></td>
			<td width="10%" class="text-center table-bordered" style="border: 1px solid #337ab7;">{{ $dia = \Carbon\Carbon::parse($venta->created_at)->format('d') }}</td>
			<td width="10%" class="text-center table-bordered" style="border: 1px solid #337ab7;">{{ $mes = \Carbon\Carbon::parse($venta->created_at)->format('m') }}</td>
			<td width="10%" class="text-center table-bordered" style="border: 1px solid #337ab7;">{{ $year = \Carbon\Carbon::parse($venta->created_at)->format('Y') }}</td>
		</tr>
	</table>
	<br>

	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-herbalife" style="margin-bottom:1px!important;">
				<div class="panel-body">
					<table width="100%">
						<tr>
							<td width="20%"><strong>CLIENTE:</strong></td>
							<td width="80%"><span>{{ $venta->cliente->nombres }} {{ $venta->cliente->apellidos }}</span></td>
						</tr>
						<tr>
							<td width="20%"><strong>DIRECCION:</strong></td>
							<td width="80%"><span>{{ $venta->cliente->direccion }}</span></td>
                        </tr>
                        <tr>
							<td width="20%"><strong>TELEFONO:</strong></td>
							<td width="80%"><span>{{ $venta->cliente->telefono }}</span></td>
						</tr>
						<tr>
							<td width="20%"><strong>NIT.:</strong></td>
							<td width="80%">
                                @if($venta->cliente->nit)
                                    <span>{{ $venta->cliente->nit }}</span>
                                @else
                                    <span>C / F</span>
                                @endif
                            </td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-herbalife">
				<div class="panel-body">
					<table class="table table-bordered">
						<thead>
							<tr class="custom-table">
								<th width="10%" class="text-center"><strong>CODIGO</strong></th>
                                <th width="30%" class="text-center"><strong>DESCRIPCION</strong></th>
                                <th width="10%" class="text-center"><strong>CANTIDAD</strong></th>
								<th width="20%" class="text-center"><strong>PRECIO UNITARIO</strong></th>
								<th width="10%" class="text-center"><strong>IMPORTE</strong></th>
							</tr>
						</thead>
						<tbody>
							@foreach($detalle as $item)
							<tr>
								<td class="text-center">{{ $item->producto_id}}</td>
                                <td>{{ $item->producto->nombre }}</td>
                                <td class="text-center">{{ $item->cantidad }}</td>
								<td class="text-center">{{ number_format($item->precio_unitario,2) }}</td>
								<td class="text-center">{{ number_format(($item->precio_unitario * $item->cantidad ),2) }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<table width="100%">
		<tr>
			<td  style="width: 33.33%">
				
			</td>
			<td style="width: 33.33%">
				
			</td>
			<td style="width: 33.33%">
				<table class="table table-bordered">
					<thead>
						<tr class="custom-table"><th class="text-center">TOTAL</th></tr>
					</thead>
					<tbody><tr><td class="text-center"><strong>Q. {{number_format( $venta->monto,2) }}</strong></td></tr></tbody>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>