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
                        <h3 class="card-title">Inventario</h3>                          
                    </div>
                    <div class="card-body">
                        <table id="listar" class="table table-bordered table-hover">
                           <thead>
                              <tr>
                                <th style="width:10%; text-align: center">No.</th>
                                <th>Categoría</th>                   
                                <th>Producto</th>
                                <th>Stock</th>
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
            'url': '/inventario/show',
            'type': 'GET'
          },
          "columns":[
              {'data':'id'},
              {'data':'categoria'},
              {'data':'producto'},
              {'data': 'stock', "render":function(data, type, row, meta){
                        var minimo = row.minimo;
                        var actual = row.stock;
                          if(actual > minimo){
                            return '<span class="badge badge-success">'+ data +'</span>'
                          }else if(actual == minimo){
                            return '<span class="badge badge-warning">'+ data +'</span>'
                          }else if(actual < minimo){
                            return '<span class="badge badge-danger">'+ data +'</span>'
                          }
                       }
              }, 
          ],
          "language": language_spanish,
          "order": [[ 0, "asc" ]]
        });
    }

</script>
@endsection
