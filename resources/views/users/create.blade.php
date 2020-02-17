@extends('layout')

@section('title', "Crear Usuario")

@section('content')
    <div class="card">
      <h4 class="card-header">
          Nuevo Usuario
      </h4>
      <div class="card-body">
        <form method="POST" action="{{ url('usuarios') }}">
            {{csrf_field()}}
            <div class="form-group">
              <label for="name">Nombre</label>
              <input type="text" @if ($errors->has('name') ) class="form-control is-invalid" @else class="form-control" @endif id="name" placeholder="Nombre" name="name" value="{{ old('name') }}">
              @if ($errors->has('name') )
                <div class="invalid-feedback">
                  {{ $errors->first('name') }}
                </div>
              @endif
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="email">Email</label>
                  <input type="email" @if ($errors->has('email') ) class="form-control is-invalid" @else class="form-control" @endif id="email" placeholder="Email" name="email" value="{{ old('email') }}">
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
              <textarea name="bio" id="bio" @if ($errors->has('bio') ) class="form-control is-invalid" @else class="form-control" @endif>{{ old('bio') }}</textarea>
              @if ($errors->has('bio') )
                <div class="invalid-feedback">
                  {{ $errors->first('bio') }}
                </div>
              @endif
            </div>

            <div class="form-group">
              <label for="profession_id">Profesión</label>
              <select name="profession_id" id="profession_id" @if ($errors->has('profession_id') ) class="form-control is-invalid" @else class="form-control" @endif>
                <option value="">Selecciona una profesión</option>
                  @foreach($professions as $profession)
                      <option value="{{ $profession->id }}"{{ old('profession_id') == $profession->id ? ' selected' : '' }}>
                          {{ $profession->title }}
                      </option>
                  @endforeach
              </select>
              @if ($errors->has('profession_id') )
                <div class="invalid-feedback">
                  {{ $errors->first('bio') }}
                </div>
              @endif
            </div>

            <div class="form-group">
              <label for="twitter">Twitter</label>
              <input type="text" @if ($errors->has('twitter') ) class="form-control is-invalid" @else class="form-control" @endif id="twitter" name="twitter" value="{{ old('twitter') }}">
              @if ($errors->has('twitter') )
                <div class="invalid-feedback">
                  {{ $errors->first('twitter') }}
                </div>
              @endif
            </div>

            <h5>Habilidades</h5>

            @foreach ($skills as $skill)
              <div class="form-check form-check-inline">
                <input name="skills[{{$skill->id}}]" 
                      class="form-check-input" 
                      type="checkbox" 
                      id="skill_{{$skill->id}}" 
                      value="{{$skill->id}}"
                      {{ old("skills.{$skill->id}") ? 'checked' : ''}}>
                <label class="form-check-label" for="skill_{{$skill->id}}">{{$skill->name}}</label>
              </div>
            @endforeach

            <h5 class="mt-3">Rol</h5>
             
            @foreach ($roles as $role => $name)
              <div class="form-check">
                <input class="form-check-input" 
                        type="radio" 
                        name="role" 
                        id="role_{{ $role }}" 
                        value="{{ $role }}"
                        {{ old('role') == $role ? 'checked' : ''}}>
                <label class="form-check-label" for="role_{{ $role }}">{{ $name }}</label>
              </div>
            @endforeach

            <div class="form-group mt-4">
              <button class="btn btn-primary float-right" type="submit">Enviar</button>
              <a class="btn btn-primary" href="{{ route('users.index') }}" role="button">Volver</a>
            </div>

          </form>
      </div>
    </div>
    


@endsection