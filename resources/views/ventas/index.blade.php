@extends('layouts.app')

@section('content')
<div id="loading"></div>
<!-- /.content-header -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header-custom">
                        <h3 class="card-title">Ventas</h3>
                        
                          <a href="{{ route('ventas.create') }}" class="btn btn-primary btn-sm float-right">Nuevo registro</a>
                        
                    </div>
                    <div class="card-body">
                        <table id="listar" class="table table-bordered table-hover">
                           <thead>
                              <tr>
                                <th style="width:10%; text-align: center">No.</th>
                                <th style="width:15%">No. factura</th>                   
                                <th>Cliente</th>
                                <th>Monto</th>
                                <th>Forma de pago</th>
                                <th>Estado de la venta</th>
                              </tr>
                            </thead> 
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
<script>
    $(document).ready(function(e){
        listar();
    });
    var  listar = function(){
        var table = $("#listar").DataTable({
            "processing": true,
            "serverSide": true,
            "destroy":true,
            "responsive": true,
            "autoWidth": false,
            "ajax":{
            'url': '/ventas/show',
            'type': 'GET'
          },
          "columns":[
              {'data': 'id', 'visible': false},
              {'data':'factura_no'},
              {'data':'cliente'},
              {'data':'monto'},
              {'data':'forma_pago'},
              {'data':'estado'}
          ],
          "language": language_spanish,
          "order": [[ 0, "asc" ]]
        });
    }

</script>
@endsection
