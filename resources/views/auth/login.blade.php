@extends('auth.contenido')

@section('login')
 <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card-group mb-0">
          <div class="card p-4 ">
            <form class="form-horizontal" action="{{ route('login') }}" method="POST" accept-charset="utf-8">
                 {{ csrf_field() }}
            <div class="card-body">
              <h1>Acceder</h1>
              <p class="text-muted">Control de acceso al sistema</p>
              <div class="input-group mb-3{{ $errors->has('usuario') ? ' has-error' : '' }}">
                <span class="input-group-addon"><i class="icon-user"></i></span>
                <input type="text" value="{{ old('usuario') }}" name="usuario" id="usuario" class="form-control" placeholder="Usuario">
               
              </div>
               {!! $errors->first('usuario','<span class="bg-danger">:message</span>') !!}
              <div class="input-group mb-4{{ $errors->has('password') ? ' has-error' : '' }}">
                <span class="input-group-addon"><i class="icon-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password"><br>
                
              </div>
              {!! $errors->first('password','<span class="bg-danger">:message</span>') !!}
              <div class="row">
                <div class="col-6">
                  <button type="submit" class="btn btn-primary px-4">Acceder</button>
                </div>
                <div class="col-6 text-right">
                  <button type="button" class="btn btn-link px-0">Olvidaste tu password?</button>
                </div>
              </div>
            </div>

            </form>
          </div>
          <div class="card text-white bg-info py-5 d-md-down-none" style="width:44%">
            <div class="card-body text-center">
              <div>
                <h2>Sistema de Ventas</h2>
                <p>Sistema de compras, Ventas desarrollado en PHP utilizando el Framework Laravel y Vue Js, con el gestor de base de datos MariaDB.</p>
                <a href="https://bit.ly/2UNp1iq" target="_blank" class="btn btn-primary active mt-3">Ver el curso!</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection
