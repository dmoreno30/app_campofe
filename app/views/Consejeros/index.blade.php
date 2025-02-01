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
  <button class="boton-aprobacion"> Consultar
  </button>
</form>
<br>
<br>
<br>
</div>
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">cod del consejero</th>
      <th scope="col">descripción del consejero</th>
      <th>Asignar el consejero al prospecto</th>
    </tr>
  </thead>
  <tbody>
    @if(isset($result))
    @foreach($result["data"] as $vendedor)

    <tr>
      <td>{{ $vendedor["cod_vendedor"]}}</td>
      <td>{{ $vendedor["dsc_vendedor"]}}</td>
      <td>
      <form
      action="/app_campofe/consejeros/blindar/{{$idProspecto}}/{{$vendedor['cod_vendedor']}}/{{$vendedor['dsc_vendedor']}}"
      method="POST" id="blindarForm">
      <input type="hidden" style="display: none;" name="result" value="{{json_encode($result)}}">

      <div id="loading" style="display: none;">
      <p>Por favor, espera. Estamos procesando tu solicitud...</p>
      <div class="spinner"></div> Spinner de carga
      </div>

      @if ($blindado == "SI")
      <button type="submit" class="blindadoOFF" disabled aria-disabled="true"
      title="El lead ya se encuentra blindado">Lead ya blindado</button>
    @else
      <button type="submit" id="submitButton" class="boton-aprobacion">Sí</button>
    @endif
      </form>

      </td>
    </tr>

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