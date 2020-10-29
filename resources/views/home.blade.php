@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
      <div class="row">
          <div class="col-md-6 offset-md-3">
              <div class="card">
                  <div class="card-header">
                      <h3 class="card-title">
                          {{ Auth::user()->nombres }} {{ Auth::user()->apellidos }}
                      </h3>
                  </div>
                  <div class="card-body">
                        <table style="width: 100%">
                            <tr style="text-align:center">
                                <th width="30px">Correo electrónico</th>
                                <td width="30px">{{ Auth::user()->email }}</td>
                            </tr>
                        </table>
                        <div class="row">
                            <div class="col-md-12">
                                <img src="{{ asset('images/funerarialogo.png') }}" alt="funeraria" height="150px" style="display:block; margin:auto">
                            </div>
                        </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</section>
@endsection
