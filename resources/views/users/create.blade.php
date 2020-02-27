@extends('layout')

@section('title', "Crear Usuario")

@section('content')

  @component('shared._card')
    @slot('header', 'Nuevo Usuario')

    @include('shared._errors')


    <form method="POST" action="{{ url('usuarios') }}">
        
        @include('users._fields')

        <div class="form-group mt-4">
          <button class="btn btn-primary float-right" type="submit">Enviar</button>
          <a class="btn btn-primary" href="{{ route('users.index') }}" role="button">Volver</a>
        </div>

    </form>
  @endcomponent 

@endsection