@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
</div>
<!-- /.content-header -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Roles de usuario</h3>
                    </div>
                    <div class="card-body">
                        <usuario-rol-component :usuario="{{ $usuario }}"></usuario-rol-component>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection         
