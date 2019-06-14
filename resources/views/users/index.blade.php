@extends('layout')

@section('title', "Usuarios")

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">{{ $title }}</h1>
        <p>
            <a href="{{ route('users.create') }}" class="btn btn-primary">Nuevo usuario</a>
        </p>
    </div>

    @if ($users->isNotEmpty())
    <table class="table">
        <thead class="thead-dark">
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Nombre</th>
            <th scope="col">Email</th>
            <th scope="col">Acción</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <th scope="row">{{$user->id}}</th>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>
                        <form action="{{ route('users.delete', $user) }}" method="POST">
                            {{ method_field('DELETE') }}
                            {!! csrf_field() !!}
                            <a href="{{ route('users.show', $user) }}" class="btn btn-primary" title="Ver Detalles">
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-success" title="Editar">
                                <i class="far fa-edit"></i>
                            </a>
                            <button type="submit" class="btn btn-danger" title="Eliminar">
                                <i class="fas fa-minus"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p>No hay usuarios registrados</p>
    @endif

@endsection
    
    
    
    
    
{{--  <ul>
    @forelse ($users as $user)
        <li>
            {{$user->name}}, ({{$user->email}})
            <a href="usuarios/152">{{$user->name}}</a>               
        </li>
    @empty
        <p>No hay usuarios registrados</p>
    @endforelse
</ul>  --}}

{{--  //Para escribir abajo de lo que ya tiene la seccion  --}}
{{-- @section('sidebar')
    @parent
    <h1>Barra lateral perzonalizada!</h1>
@endsection --}}


{{--  //Para sobre escribir la seccion  --}}
{{--  @section('sidebar')
    <h1>Barra lateral perzonalizada!</h1>
@endsection  --}}

{{--  //OTRA FORMA DE HACERLO  --}}

{{--  @include('header')
    <div class="row mt-3">
        <div class="col-8">

            <h1>{{$title}}</h1>

            <hr>

            <ul>
                @forelse ($users as $user)
                    <li>{{$user}}</li>
                @empty
                    <p>No hay usuarios registrados</p>
                @endforelse
            </ul>

        </div>
        <div class="col-4">
            @include('sidebar')
        </div>
    </div>
@include('footer')  --}}
    
{{--  //DIFERENTE LOGICA PARA MOSTRAR LOS USUARIOS  --}}
    {{--  @unless(empty($users))
        <ul>
            @foreach($users as $user)
                <li>{{$user}}</li>
            @endforeach
        </ul>
    @else
        <p>No hay usuarios registrados</p>
    @endif  --}}

    {{--  @empty($users)
        <p>No hay usuarios registrados</p>
    @else
        <ul>
            @foreach($users as $user)
                <li>{{$user}}</li>
            @endforeach
        </ul>
    @endempty  --}}
