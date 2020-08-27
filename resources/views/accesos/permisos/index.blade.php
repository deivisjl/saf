@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
</div>
<!-- /.content-header -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header-custom">
                        <h3 class="card-title">Permisos</h3>
                        @can('Crear permisos')
                          <a href="{{ route('permisos.create') }}" class="btn btn-primary btn-sm float-right">Nuevo registro</a>
                        @endcan
                    </div>
                    <div class="card-body">
                        <table id="listar" class="table table-bordered table-hover">
                           <thead>
                              <tr>
                                <th style="width:15%; text-align: center">No.</th>
                                <th>Nombre</th>                   
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
            'url': '/permisos/show',
            'type': 'GET'
          },
          "columns":[
              {'data': 'id'},
              {'data': 'nombre'},   
              {'defaultContent':'<a href="" class="editar btn btn-success btn-xs"  data-toggle="tooltip" data-placement="top" title="Editar registro"><i class="fa fa-edit"></i> Editar</a> <a href="" class="borrar btn btn-danger btn-xs"  data-toggle="tooltip" data-placement="top" title="Borrar registro"><i class="fa fa-trash"></i> Eliminar</a>', "orderable":false}
          ],
          "language": language_spanish,
          "order": [[ 0, "asc" ]]
        });
        obtener_data_editar("#listar tbody",table);
    }

    var obtener_data_editar = function(tbody,table){
         $(tbody).on("click","a.editar",function(e){
             e.preventDefault();

            var data = table.fnGetData($(this).parents("tr"));
            
            var id = data.id;
             window.location.href = "/permisos/" + id + "/edit";
          });

         $(tbody).on("click","a.borrar",function(e){
             e.preventDefault();
             
            var data = table.fnGetData($(this).parents("tr"));
            var id = data.id;
             Swal.fire({
                  title: '¿Está seguro de eliminar este registro?',
                  text: data.nombre,
                  type: 'question',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Aceptar',
                  cancelButtonText: 'Cancelar'
                }).then((result) => {
                   if (result.value) {
                      axios.delete('/permisos/'+id)
                          .then(response => {
                              Toastr.success(response.data.data,'Mensaje')
                                $('#listar').DataTable().ajax.reload();
                              
                          })
                          .catch(error => {
                              if (error.response) {
                                  Toastr.error(error.response.data.error,''); 
                              }else{
                                  Toastr.error('Ocurrió un error: ' + error,'Error');
                              }
                          });
                   }
                    
                });
             
          });
      }
</script>
@endsection