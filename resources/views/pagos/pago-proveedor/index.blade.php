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
                        <h3 class="card-title">Cuentas por pagar</h3>
                    </div>
                    <div class="card-body">
                        <table id="listar" class="table table-bordered table-hover">
                           <thead>
                              <tr>
                                <th style="width:15%; text-align: center">No.</th>
                                <th>Proveedor</th>                   
                                <th>No. factura</th>
                                <th>Monto</th>
                                <th>Saldo</th>
                                <th>Acción</th>
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
            'url': '/pago-proveedores/show',
            'type': 'GET'
          },
          "columns":[
              {'data': 'id', 'visible':false},
              {'data': 'nombre'},
              {'data': 'factura_no'},
              {'data': 'monto', "render":function(data, type, row, meta){
                        return '<span> Q.'+ data +'</span>'
                       }
              },   
              {'data': 'saldo', "render":function(data, type, row, meta){
                        return '<span> Q.'+ data +'</span>'
                       }
              },
              {'defaultContent':'<a href="" class="detalle btn btn-success btn-xs"  data-toggle="tooltip" data-placement="top" title="Detalle de registro"><i class="fa fa-edit"></i> Detalle</a> <a href="" class="pagar btn btn-primary btn-xs"  data-toggle="tooltip" data-placement="top" title="Abonar pago"><i class="fa fa-money-bill-alt"></i> Abonar pago</a>', "orderable":false}
          ],
          "language": language_spanish,
          "order": [[ 0, "asc" ]]
        });
        obtener_data_editar("#listar tbody",table);
    }

    var obtener_data_editar = function(tbody,table){
         $(tbody).on("click","a.detalle",function(e){
             e.preventDefault();

            var data = table.fnGetData($(this).parents("tr"));
            
            var id = data.id;
             window.location.href = "/pago-proveedores/" + id + "/detalle";
          });
          $(tbody).on("click","a.pagar",function(e){
             e.preventDefault();

            var data = table.fnGetData($(this).parents("tr"));
            
            var id = data.id;
             window.location.href = "/pago-proveedores/" + id + "/abonar";
          });
         
      }
</script>
@endsection
