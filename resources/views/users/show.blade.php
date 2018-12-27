@extends('layout')

@section('title', "Usuarios {$user->id}")

@section('content')

    <h1>Usuario #{{$user->id}}</h1>
    <p>Mostrando detalle del usuario: {{$user->name}}</p>
    <p>Mostrando detalle del usuario: {{$user->email}}</p>

    <p>
        {{--  <a class="btn btn-primary btn-lg active" href="{{ route('users.index') }}">Regresar al listado</a>  --}}

        <a class="btn btn-primary" href="{{ route('users.index') }}" role="button">Volver</a>
        {{--  
        <a href="{{ url()->previous() }}"></a>
        <a href="{{ action('UserController@index') }}">Regresar al listado</a>
          --}}
    </p>

    {{--  <div class="row content-panel" >
        <div class="col-md-4 profile-text mt mb centered">
            <div class="right-divider hidden-sm hidden-xs">
            <h4>1922</h4>
            <h6>FOLLOWERS</h6>
            <h4>290</h4>
            <h6>FOLLOWING</h6>
            <h4>$ 13,980</h4>
            <h6>MONTHLY EARNINGS</h6>
            </div>
        </div>
        <!-- /col-md-4 -->
        <div class="col-md-4 profile-text">
            <h3>Sam Soffes</h3>
            <h6>Main Administrator</h6>
            <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC.</p>
            <br>
            <p><button class="btn btn-theme"><i class="fa fa-envelope"></i> Send Message</button></p>
        </div>
        <!-- /col-md-4 -->
        <div class="col-md-4 centered">
            <div class="profile-pic">
            <p><img src="img/ui-sam.jpg" class="img-circle"></p>
            <p>
                <button class="btn btn-theme"><i class="fa fa-check"></i> Follow</button>
                <button class="btn btn-theme02">Add</button>
            </p>
            </div>
        </div>
        <!-- /col-md-4 -->
    </div>  --}}

@endsection