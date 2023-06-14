@extends('adminlte::page')

@section('title', 'Buscador de vacas')

@section('content_header')
    <h1 class="abs-center" > </h1>
@stop

@section('content')
   <div class="card">
    <div class="card-body">
      <div class="   w-auto">
        <div class="col margin  w-10">
          <div class="text-center mb-10  w-10"> 
            <div class= "m-3">
              <h1>Buscador de vacas</h1>
            </div>
            <button type="button" class="btn btn-primary btn-lg " data-toggle="modal" data-target="#exampleModalCenter">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg> 
              Actualizar lista
            </button>
             
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">
                            Actualizar lista de vacas
                          </h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <div class="form-group m-3">
                            <form action="/Vacas/actualizar_pago" action="{{'submit'}}" method="post">
                              @method('PUT')
                              @csrf
                                <label for="aldea">Aldea</label>
                                <select  name="id_aldea" class="form-control" id ="id_aldea">
                                    @foreach($aldeas as $aldea)
                                        <option value="{{$aldea->id}}">{{$aldea->nombre}}({{$aldea->coord_x }}/{{$aldea->coord_y }})</option>
                                    @endforeach
                                </select>
                                <label for="aldea">Vacas</label>
                                <input type="text" name="madera" class="form-control" id ="tropas"  >
                                <button type="submit" class="btn btn-outline-ligh bg-black m-3">Guardar información</button>
                            </form>
                          </div>
                      </div>
                    </div>
                </div>
            </div>
            @if($estado =='no')
            <h1>Búsqueda de vacas</h1>
            <form action="/Vacas/calculovacas"  action="{{'submit'}}" method="post">
                @method('PUT')
                @csrf
             
                <input type="hidden" name="_method" value="PUT">
                    <div class="mb-3">
                    <label class="form-label">Aldea lanzamiento</label>
                    
                    <select class="form-control" aria-label="Default select example" name = "idAldea">
                            @foreach($aldeas as $aldea)
                               
                                    <option value="{{$aldea->id}}">{{$aldea->nombre}}</option>                    
                            @endforeach
                        </select>

                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Días inactivo</label>
                        <input name="dias" type="number" min="2" pattern="^[0-9]+"  VALUE = "2" class="form-control"  >
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Max distancia</label>
                        <input name="distancia" type="number" min="2" pattern="^[0-9]+" VALUE = "0"class="form-control" >
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Max cambio de poblacion</label>
                        <input name="poblacion" type="number" min="1" pattern="^[0-9]+" VALUE = "0" class="form-control" >
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mínima población de aldeas</label>
                        <input name="pobAldeas" type="number" min="0" pattern="^[0-9]+" VALUE = "0"class="form-control" >
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" value="1" id ="" VALUE = "0"name="PerdidaPobl" checked>   
                        <label class="form-check-label" for="flexCheckChecked"> Mostrar cuentas sin perdida de población</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" value="1" VALUE = "0" name="sinAlianza" checked>   
                        <label class="form-check-label" for="flexCheckChecked"> Mostrar cuentas sin alianza</label>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Búsqueda</button>
                   </div>
                </form> 
            @else
         
            <h1>Búsqueda de vacas</h1>
                <form action="/Vacas/calculovacas"  action="{{'submit'}}" method="post">
                            @method('PUT')
  @csrf
                    <div class="mb-3">
                    <label class="form-label">Aldea lanzamiento</label>
                    <select class="form-label" aria-label="Default select example" name = "idAldea">
                        @foreach($aldeas as $aldea){
                            @if($aldea->id==$busqueda['aldeaLanza'])
                                <option value='{{$aldea->id}}' selected = 'selected'>{{$aldea->nombre}}</option>
                            @else
                                <option value='{{$aldea->id}}' >{{$aldea->nombre}}</option>
                            @endif    
                        @endforeach      
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Días desde el cambio</label>
                        <input name="dias" type="number" min="2" pattern="^[0-9]+" class="form-control" value = "{{$busqueda['dias']}}" >
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Max distancia</label>
                        <input name="distancia" type="number" min="2" pattern="^[0-9]+" class="form-control"  value = "{{$busqueda['distancia']}}" >
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Max cambio de poblacion</label>
                        <input name="poblacion" type="number" min="1" pattern="^[0-9]+" class="form-control" value = "{{$busqueda['cambiopob']}}" >
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mínima población de aldeas</label>
                        <input name="pobAldeas" type="number" min="0" pattern="^[0-9]+" class="form-control" value = "{{$busqueda['minpob']}}"  >
                    </div>
                    <div class="form-check form-switch">
                    
                        @if( $busqueda['PerdidaPobl']=='1')
                            <input class="form-check-input" type="checkbox" value="1" id ="" name="PerdidaPobl" checked>   
                        @else
                            <input class="form-check-input" type="checkbox" value="1" id ="" name="PerdidaPobl">   
                        @endif    
                        <label class="form-check-label" for="flexCheckChecked"> Mostrar cuentas sin perdida de población</label>
                    </div>
                    <div class="form-check form-switch">
                        
                        @if( $busqueda['sinAlianza']=='1')
                            <input class="form-check-input" type="checkbox" value="1" name="sinAlianza" checked>      
                        @else
                            <input class="form-check-input" type="checkbox" value="1" name="sinAlianza" >     
                         @endif    
                        
                        <label class="form-check-label" for="flexCheckChecked"> Mostrar cuentas sin alianza</label>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Búsqueda</button>
                   </div>
                </form> 
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-center ">
                    <div class="card-body "> 
                        
                    </div>  
                </div>

                <table id="taldeas" class="table table-striped  table-bordered table-hover">
                    <thead>
                            <tr>
                                <th>Distancia</th>
                                <th>Nombre aldea</th>
                                <th>Cuenta</th>
                                <th>Raza</th>
                                <th>Nombre alianza</th>
                                <th>Población aldea</th> 
                                <th>Población cuenta</th> 
                                <th>Diferencia población cuenta</th> 
                                <th>Opciones</th>                        
                            </tr>
                        </thead>
                    <tbody>
                        @foreach($info as $posibleVacas)
                        <tr>
                                <td>{{$posibleVacas->distancia}}  </td>
                                <td>{{$posibleVacas->nombrealdea}} <a target="_blank" href="{{$posibleVacas->rutaServer}}/position_details.php?x={{$posibleVacas->coord_x}}&y={{$posibleVacas->coord_y}}"   >{{$posibleVacas->coord_x }}{{ __('/') }}{{$posibleVacas->coord_y }}</a></td>
                                <td><a target="_blank" href="{{$posibleVacas->rutaServer}}/profile/{{$posibleVacas->idCuenta}}" >{{$posibleVacas->NombreCuenta}} </a></td>
                                <td>{{$posibleVacas->raza}}</td>
                                <td><a target="_blank"  href="{{$posibleVacas->rutaServer}}/alliance/{{$posibleVacas->idAlianza}}">{{$posibleVacas->NombreAlianza}} </a> </td>
                                <td>{{$posibleVacas->poblacion_aldea}}</td>
                                <td>{{$posibleVacas->hoy}}</td>
                                <td>{{$posibleVacas->dif_poblacion_cuenta}}</td>
                                <td>
                                    <form action="/vacas/insertarVacas"  action="{{'submit'}}" method="post">
                                        @method('PUT')
                                        @csrf
                                        <button type="submit" class="btn btn-success float-right" >Añadir</button>
                                        <input  name="idAldeaVaca" type="hidden" value="{{$posibleVacas->id_aldea}}">
                                        <input  name="idAldea" type="hidden" value="{{$busqueda['aldeaLanza']}}">
                                        <input  name="dias" type="hidden" value="{{$busqueda['dias']}}">
                                        <input  name="distancia" type="hidden" value="{{$busqueda['distancia']}}">
                                        <input  name="poblacion" type="hidden" value="{{$busqueda['cambiopob']}}">
                                        <input  name="pobAldeas" type="hidden" value="{{$busqueda['minpob']}}">
                                        @if( $busqueda['PerdidaPobl']=='1')
                                        <input  name="PerdidaPobl" type="hidden" value="1">
                                        @else
                                        <input  name="PerdidaPobl" type="hidden" value="">  
                                        @endif 
                                        @if( $busqueda['sinAlianza']=='1')
                                            <input  name="sinAlianza" type="hidden" value="1">
                                        @else
                                         <input  name="sinAlianza" type="hidden" value="">  
                                        @endif       
                                    </form>
                                    </td>
                        </tr>   
                        @endforeach
                    </tbody>
                </table>

            @endif
          </div>
        </div>
      </div>
    </div>
  </div>       
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js" ></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js" ></script>
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js" ></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js" ></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js" ></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" ></script>


<script>
 $(function () {
    $("#taldeas").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": []
    }).buttons().container().appendTo('#taldeas_wrapper .col-md-6:eq(0)');
    
  });
  
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
 
  <?php

echo $mensaje;
?> 
</script>
@stop