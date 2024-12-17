<!-- /views/Principal/index.blade.php -->
@extends('app')
@section('title', 'Página de consejeros Campo fe')
@section('content')

  <div class="titulo">  
    <h1>Lista de consejeros disponibles</h1>
  </div>
  <form action="/app_campofe/consejeros/listar" Method="Post" class="search-container">
    <input type="hidden" style="display: none;" value="consultar" name="consultar">
    <input type="hidden" style="display: none;" value="{{ $idProspecto ?? 'Valor por defecto' }}" name="idProspecto">
    <button class="boton-aprobacion" > Consultar
    </button>
  </form>
  <br>
  <br>
  <br>
</div>
<table class="table table-striped">
<thead>
  <tr>
    <th scope="col">cod de vendedor</th>
    <th scope="col">descripción del vendedor</th>
    <th>Asignar Consultor al prospecto</th>
  </tr>
</thead>
<tbody>
  @if(isset($result))
  @foreach($result["data"] as $vendedor)
  @foreach($vendedor as $item)
  <tr>
    <td>{{ $vendedor["cod_vendedor"]}}</td>
    <td>{{ $vendedor["dsc_vendedor"]}}</td>
    <td>
      <form action="/app_campofe/consejeros/blindar/{{$idProspecto}}/{{$vendedor["cod_vendedor"]}}" method="POST">
        <input type="hidden" style="display: none;" name="result" value="{{json_encode($result) }}">
        <button type="submit" class="boton-aprobacion">Si</button>
    </form>
     
  </td>
  </tr>
  @endforeach
  @endforeach
  @else
    <!-- Este bloque se ejecuta si $result no está definido -->
    <tr>
        <td colspan="4">Realiza la consulta</td>
    </tr>
  @endif
</tbody>
</table>
@endsection

