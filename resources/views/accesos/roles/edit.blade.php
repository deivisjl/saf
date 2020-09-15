@extends('layouts.app')

@section('content')
<!-- /.content-header -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Editar registro</h3>
                    </div>
                    <div class="card-body">
                        <editar-rol-component :rol="{{ $rol }}"></editar-rol-component>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection         
