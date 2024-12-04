<!-- /views/Principal/index.blade.php -->

@extends('app')

@section('title', 'Página de Inicio Campo fe')

@section('content')
  <div class="titulo">  
    <h1>Consulta datos del consejero</h1>
  </div>
    
    <div class="search-container">
      <form action="/app_campofe/search" Method="Post">
        <input type="text" class="search-box" placeholder="Consultar..." name="cedula">
        <button class="search-button">
          <img src="https://cdn.icon-icons.com/icons2/1105/PNG/512/loupe_78956.png" alt="Consultar" class="search-icon">
        </button>
      </form>
    </div>
    
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Numero DNI</th>
          <th scope="col">Nombre</th>
          <th scope="col">Apellido</th>
          <th scope="col">Esta blindado</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row"></th>
          <td>Mark</td>
          <td>Otto</td>
          <td>@mdo</td>
        </tr>
      </tbody>
    </table>
    @if (isset($idProspecto))
    {{ $idProspecto }}
    @endif
    
    @if  (isset($cedula))
    {{$cedula}}
    @endif



@endsection