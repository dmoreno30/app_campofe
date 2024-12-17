<!-- /views/Principal/index.blade.php -->
@extends('app')
@section('title', 'Página de Inicio Campo fe')
@section('content')
  <div class="titulo">  
    <h1>Consulta datos del Cliente</h1>
  </div>
    <div class="search-container">
      <form action="/app_campofe/search" Method="Post" class="search-container">
        <input type="number" class="search-box" placeholder="Consultar..." name="cedula" required>
        <input type="hidden" style="display: none;" value="{{ $idProspecto ?? 'Valor por defecto' }}" name="idProspecto">
        <select name="TipoDocumento" id="documento" class="selectordocument" required>
          <option value="DNI">DNI</option>
          <option value="CE">CE</option>
          <option value="PASS">PASS</option>
        </select>
        <button class="search-button">
          <img src="https://cdn.icon-icons.com/icons2/1105/PNG/512/loupe_78956.png" alt="Consultar" class="search-icon">
        </button>
      </form>
    </div>
    <div>
      @if(isset($result))
        @if (isset($result["data"]["codigo"]))
          @switch($result["data"]["codigo"])
              @case(0)
                  <p class="alerta-danger">El Numero indicado NO existe en la RENIEC</p>
                  @break
              @case(1)
                @if ($result["data"]["mensaje"] == "El prospecto <b>NO</b> existe")
                    <p class="alerta-danger">Resultado: {!!$result["data"]["mensaje"]!!}  </p>
                @else
                    <p class="alerta-success">Resultado: {!!$result["data"]["mensaje"]!!}  </p>
                @endif
                  @break
          @endswitch
        @endif
      @endif
    </div>
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">Numero DNI</th>
          <th scope="col">Nombre</th>
          <th scope="col">Apellido</th>
          <th scope="col">Actualizar en Bitrix24</th>
        </tr>
      </thead>
      <tbody>
        @if(isset($result))
        @if(isset($result["data"]["codigo"]) && $result["data"]["codigo"] == 0)
            <tr>
                <td colspan="4">No existe el usuario en la Reniec, por favor revisa el numero ingresado</td>
            </tr>       
        @else
            <tr>
                <th>{{$result["data"]["reniec"]["num_dni"]}}</th>
                <td>{{$result["data"]["reniec"]["nombres"]}}</td>
                <td>{{$result["data"]["reniec"]["ape_paterno"]}} {{$result["data"]["reniec"]["ape_materno"]}}</td>
                <td>
                  <form action="/app_campofe/prospectos/{{$idProspecto}}" method="POST">
                    <input type="hidden" style="display: none;" name="result" value="{{json_encode($result) }}">
                    <button type="submit" class="boton-aprobacion">Si</button>
                </form>
                 
              </td>
                                        
            </tr>
        @endif
    @else
        <!-- Este bloque se ejecuta si $result no está definido -->
        <tr>
            <td colspan="4">Realiza la busqueda</td>
        </tr>
    @endif
      </tbody>
    </table>
@endsection   

