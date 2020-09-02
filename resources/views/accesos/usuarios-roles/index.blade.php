@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
</div>
<div id="loading"></div>
<!-- /.content-header -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Usuarios</h3>
                    </div>
                    <div class="card-body">
                        <table id="listar" class="table table-bordered table-hover">
                           <thead>
                              <tr>
                                <th style="width:15%; text-align: center">No.</th>
                                <th>Nombres</th>  
                                <th>Apellidos</th>  
                                <th>Correo electrónico</th>                   
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
            'url': '/usuarios-roles/show',
            'type': 'GET'
          },
          "columns":[
              {'data': 'id'},
              {'data': 'nombres'}, 
              {'data': 'apellidos'},
              {'data': 'email'},    
              {'defaultContent':'<a href="" class="roles btn btn-info btn-xs"  data-toggle="tooltip" data-placement="top" title="Modificar roles"><i class="fa fa-key"></i> Roles</a>', "orderable":false}
          ],
          "language": language_spanish,
          "order": [[ 0, "asc" ]]
        });
        obtener_data_editar("#listar tbody",table);
    }

    var obtener_data_editar = function(tbody,table){
         $(tbody).on("click","a.roles",function(e){
             e.preventDefault();

            var data = table.fnGetData($(this).parents("tr"));
            
            var id = data.id;
             window.location.href = "/usuarios-roles/" + id + "/edit";
          });
      }
</script>
@endsection
