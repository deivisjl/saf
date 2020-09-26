@extends('layouts.app')

@section('content')
<div id="loading"></div>
<!-- /.content-header -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="card">
                    <div class="card-header-custom">
                        <h3 class="card-title">Proveedores</h3>
                        
                          <a href="{{ route('proveedores.create') }}" class="btn btn-primary btn-sm float-right">Nuevo registro</a>
                        
                    </div>
                    <div class="card-body">
                        <table id="listar" class="table table-bordered table-hover">
                           <thead>
                              <tr>
                                <th style="width:15%; text-align: center">No.</th>
                                <th>Nombre</th> 
                                <th>NIT</th>                  
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
            'url': '/proveedores/show',
            'type': 'GET'
          },
          "columns":[
              {'data': 'id'},
              {'data': 'nombre'},   
              {'data': 'nit'},   
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
             window.location.href = "/proveedores/" + id + "/edit";
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
                      $("#loading").removeClass("hidden");
                      $("#loading").addClass("block-loading");
                      axios.delete('/proveedores/'+id)
                          .then(response => {
                              $("#loading").addClass("hidden");
                              $("#loading").removeClass("block-loading");
                              Toastr.success(response.data.data,'Mensaje')
                              table._fnAjaxUpdate() //actualizar datatable
                          })
                          .catch(error => {
                              $("#loading").addClass("hidden");
                              $("#loading").removeClass("block-loading");
                              if (error.response.data.code === 423) {
                                  Toastr.error(error.response.data.error,'Mensaje'); 
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
