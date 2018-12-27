@extends('layout')

@section('title', "Editar Usuario")

@section('content')

    <h1>Editar Usuario</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
          <h4>Por favor corrige los errores debajo:</h4>
            {{--  <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>  --}}
        </div>
    @endif

    <form method="POST" action="{{ url('usuarios') }}">
        {{csrf_field()}}
        <div class="form-group">
          <label for="name">Nombre</label>
          <input type="text" @if ($errors->has('name') ) class="form-control is-invalid" @else class="form-control" @endif id="name" placeholder="Nombre" name="name" value="{{ old('email', $user->name) }}">
          @if ($errors->has('name') )
            <div class="invalid-feedback">
              {{ $errors->first('name') }}
            </div>
          @endif
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
              <label for="email">Email</label>
              <input type="email" @if ($errors->has('email') ) class="form-control is-invalid" @else class="form-control" @endif id="email" placeholder="Email" name="email" value="{{ old('email', $user->email) }}">
              @if ($errors->has('email') )
                <div class="invalid-feedback">
                  {{ $errors->first('email') }}
                </div>
              @endif
            </div>
            <div class="form-group col-md-6">
              <label for="password">Password</label>
              <input type="password" @if ($errors->has('password') ) class="form-control is-invalid" @else class="form-control" @endif id="password" placeholder="Password" name="password">
              @if ($errors->has('password') )
                <div class="invalid-feedback">
                  {{ $errors->first('password') }}
                </div>
              @endif
            </div>
          </div>
          <div class="form-group">
            <label for="bio">Bio</label>
            <textarea name="bio" id="bio" @if ($errors->has('bio') ) class="form-control is-invalid" @else class="form-control" @endif>{{ old('bio', $user->bio) }}</textarea>
            @if ($errors->has('bio') )
              <div class="invalid-feedback">
                {{ $errors->first('bio') }}
              </div>
            @endif
          </div>
          <div class="form-group">
            <label for="twitter">Twitter</label>
            <input type="text" @if ($errors->has('twitter') ) class="form-control is-invalid" @else class="form-control" @endif id="twitter" name="twitter" value="{{ old('twitter', $user->twitter) }}">
            @if ($errors->has('twitter') )
              <div class="invalid-feedback">
                {{ $errors->first('twitter') }}
              </div>
            @endif
          </div>
        
        <button class="btn btn-primary float-right" type="submit">Actualizar</button>
        <a class="btn btn-primary" href="{{ route('users.index') }}" role="button">Volver</a>

    </form>

@endsection