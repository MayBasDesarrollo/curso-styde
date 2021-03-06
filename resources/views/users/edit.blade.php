@extends('layout')

@section('title', "Editar Usuario")

@section('content')

  {{-- COMPONENT CARD IRIA CARD POR LO QUE SE REGISTRO EN AppServiceProvider --}}
  @component('shared._card')
    @slot('header', 'Editar Usuario')
    
    {{-- TODO ESTO ERA LO QUE ESTABA DENTRO DEL CONTEN QUE QUEDO COMO SLOT --}}
    
    @include('shared._errors')

    <form method="POST" action="{{ url("usuarios/{$user->id}") }}">
      
        {{ method_field('PUT') }}
  
        {{-- @render('UserFields', ['user' => $user]) --}}
        
        <div class="form-group mt-4">
          <button class="btn btn-primary float-right" type="submit">Actualizar</button>
          <a class="btn btn-primary" href="{{ route('users.index') }}" role="button">Volver</a>
        </div>
  
    </form>
  @endcomponent  

@endsection