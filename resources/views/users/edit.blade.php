@extends('layout')

@section('title', "Editar Usuario")

@section('content')

  <div class="card">
      <h4 class="card-header">
          Editar Usuario
      </h4>
      <div class="card-body">

        @include('shared._errors')

        <form method="POST" action="{{ url("usuarios/{$user->id}") }}">
          
            {{ method_field('PUT') }}

            @include('users._fields')
            
          <div class="form-group mt-4">
            <button class="btn btn-primary float-right" type="submit">Actualizar</button>
            <a class="btn btn-primary" href="{{ route('users.index') }}" role="button">Volver</a>
          </div>
    
        </form>
      </div>
  </div>

@endsection