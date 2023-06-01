@extends('adminlte::page')

@section('title', 'Mis aldeas')

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
              <h1>Mis aldeas</h1>
            </div>
            <button type="button" class="btn btn-primary btn-lg " data-toggle="modal" data-target="#exampleModalCenter">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg> 
              Nueva aldea
            </button>
            <button type="button" class="btn btn-primary btn-lg " data-toggle="modal" data-target="#produccion">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg> 
              Actualizar producción
            </button>
            <button type="button" class="btn btn-primary btn-lg " data-toggle="modal" data-target="#pc">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg> 
              Actualizar pc
            </button>
            <div class="modal fade" id="produccion" tabindex="-1" role="dialog" aria-labelledby="produccion" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">
                            Actualizar producción
                          </h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <div class="form-group m-3">
                            <form action="/Aldeas/actualizarprod" action="{{'submit'}}" method="post">
                              @method('PUT')
                              @csrf
                              <input type="text" name="madera" class="form-control" id ="tropas"  >
                                <button type="submit" class="btn btn-outline-ligh bg-black m-3">Guardar información</button>
                            </form>
                          </div>
                      </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="pc" tabindex="-1" role="dialog" aria-labelledby="pc" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle"> Actualizar pc</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <div class="form-group m-3">
                            <form action="/Aldeas/actualizarpc" action="{{'submit'}}" method="post">
                              @method('PUT')
                              @csrf
                              <input type="text" name="madera" class="form-control" id ="tropas"  >
                                <button type="submit" class="btn btn-outline-ligh bg-black m-3">Guardar información</button>
                            </form>
                          </div>
                      </div>
                    </div>
                </div>
            </div>
   
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">Nueva aldea</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <div class="form-group m-3">
                            <form action="/Aldeas/crear" action="{{'submit'}}" method="post">
                              @method('PUT')
                              @csrf
                                <label for="nombreParam">Nombre aldea</label>
                                <input type="text" name="nombre" class="form-control" id ="nombre">
                                <label for="nombreParam">Tipo de aldea</label>
                                <select  name="tipoaldea" class="form-control" id ="tipoaldea"> 
                                  @foreach($tipos as $tipo)
                                    <option value="{{$tipo->valor}}" > {{$tipo->nombre}}</option>
                                  @endforeach 
                                 </select>
                                <label for="nombreParam">Coordenada x</label>
                                <input type="number" name="coor_x" class="form-control" id ="coor_x"   value = "0" pattern="[-]*[0-9]+"required>
                                <label for="nombreParam">Coordenada y</label>
                                <input type="number" name="coor_y" class="form-control" id ="coor_y" value = "0" pattern="[-]*[0-9]+" required>
                                
                                <label for="nombreParam">Fiesta pequeña</label>
                                <select  name="f_pequena" class="form-control" id ="f_pequena">  
                                  <option value="0">No</option> 
                                  <option value="1">Si</option> 
                                </select> 
                                <label for="nombreParam">Fiesta grande:</label>
                                <select  name="f_grande" class="form-control" id ="f_grande"> 
                                  <option value="0">No</option> 
                                  <option value="1">Si</option>   
                                </select>  
 
                                <div class="form-check form-switch" >
                                  <input class="form-check-input" name = "check"type="checkbox" id="flexSwitchCheckDefault" checked>
                                  <label class="form-check-label" for="flexSwitchCheckDefault">Generación automática de tareas</label>
                                </div>
                                <label for="nombreParam">Tareas</label>
                                <select  name="tipotarea" class="form-control" id ="tipoaldea"> 
                                  @foreach($tareas as $tarea)
                                    <option value="{{$tarea->valor}}" > {{$tarea->nombre}}</option>
                                  @endforeach 
                                 </select>
                                <button type="submit" class="btn btn-outline-ligh bg-black m-3">Crear aldea</button>
                            </form>
                          </div>
                      </div>
                    </div>
                </div>
            </div>
          </div>   
        </div>
        <div class="col table-responsive w-auto">
              <table id="example1" class="table table-bordered table-striped ">
              <thead class="table-dark">
                <tr>
                  <th>Nombre aldea</th>
                  <th>Tipo aldea</th>
                  <th>Prod. madera</th>
                  <th>Prod. barro</th>
                  <th>Prod. hierro</th>
                  <th>Prod. cereal</th>
                  <th>Total prod.</th>
                  <th>PC</th>
                  <th>PC bono alianza</th>
                  <th>Fiesta pequeña</th>
                  <th>Fiesta grande</th>
                  <th>PC generado fiestas</th>
                  <th>PC totales</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $suma_madera_total = 0;
                  $suma_barro_total = 0;
                  $suma_hierro_total = 0;
                  $suma_cereal_total = 0;
                  $suma_materias_total = 0;
                  $suma_puntosc_total = 0;
                  $pc_totales = 0;
                  $pc_todos = 0;
                  $pc_totales_fiestas =0;
                  $pc_totales_alianza  = 0;
                @endphp
                @foreach($aldeas as $aldea)
                @php
                $f_peque = 0;
                $pc_totales_aldea = 0;
                $pc_aldea_fiesta = 0;
                $suma_materias_aldea = (int)$aldea->madera+(int)$aldea->barro+(int)$aldea->hierro+(int)$aldea->cereal;
                $suma_madera_total =  $suma_madera_total + (int)$aldea->madera;
                $suma_barro_total = $suma_barro_total+(int)$aldea->barro;
                $suma_hierro_total =  $suma_hierro_total+ (int)$aldea->hierro;
                $suma_cereal_total = $suma_cereal_total+(int)$aldea->cereal;
                $suma_materias_total = $suma_materias_total + $suma_materias_aldea;
                
               //echo $putnos_fiesta_grande;
               //echo $aldea->tiempo_pequena;
                if($aldea->fiesta_pequena==1){
                    if($aldea->puntos_cultura>$putnos_fiesta_pequeña)
                    {
                        $f_peque = $putnos_fiesta_pequeña;
                    }else{
                        $f_peque =$aldea->puntos_cultura;
                    }
                    if($aldea->ayuntamiento>=1){
                        $pc_aldea_fiesta = round(((1/$aldea->tiempo_pequena)*$velocidad_fiesta)*$f_peque);
                    }else{
                        $pc_aldea_fiesta = 0;
                    }
                    $fp = 'Si';
                }else{
                    $fp = 'No';
                }
                if($aldea->fiesta_grande==1){
                    if($aldea->ayuntamiento>9){
                        $pc_aldea_fiesta = round(((1/$aldea->tiempo_grande)*$velocidad_fiesta)*$putnos_fiesta_grande);
                       
                    }else{
                        $pc_aldea_fiesta = 0;
                    }
                    
                    $fg = 'Si';
                }else{
                    $fg = 'No';
                }


                $pc_alianza = round(($filosofia*$aldea->puntos_cultura)-$aldea->puntos_cultura);
                $pc_totales_aldea = $pc_aldea_fiesta+$aldea->puntos_cultura;
                $pc_todos = $pc_todos + $aldea->puntos_cultura+$pc_aldea_fiesta+$pc_alianza;
                $suma_puntosc_total =$suma_puntosc_total + $aldea->puntos_cultura;
                $pc_totales_fiestas = $pc_totales_fiestas + $pc_aldea_fiesta;
               
                $pc_totales_alianza = $pc_totales_alianza  + $pc_alianza;
            @endphp
                  <tr>
                    <th>{{$aldea->nombre}} ({{$aldea->coord_x}}/{{$aldea->coord_y}})</th>
                    <th>{{$aldea->tipo_aldea}}</th>
                    <th>{{$aldea->madera}}</th>
                    <th>{{$aldea->barro}}</th>
                    <th>{{$aldea->hierro}}</th>
                    <?php
                        if($aldea->cereal<0) 
                        {
                            $class = "table-danger";
                        }else {
                          $class = "";
                        } 

                       
                       ?>
                        
                    <th class= "<?php echo $class; ?>">{{$aldea->cereal}}</th>
                    <th>{{$suma_materias_aldea}} </th>
                    <th>{{$aldea->puntos_cultura}}</th>
                    <th>{{$pc_alianza}}</th>
                    <th>{{$fp}}</th>
                    <th>{{$fg}}</th>
                    <th>{{$pc_aldea_fiesta}}</th>
                    <th>{{$pc_totales_aldea}}</th>
                    <th> 
                      <div class="margin">
                        <div class="btn-group  ">
                          <button type="button" class="btn   btn-info btn-info">Acciones</button>
                          <button type="button " class="btn   dropdown-toggle dropdown-icon btn-info" data-toggle="dropdown">
                          <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                          <button class="dropdown-item btn-info" data-toggle="modal" data-target="#editarAldea-{{$aldea->id_aldea}}">Editar aldea</button>
                          <button class="dropdown-item btn-info" data-toggle="modal" data-target="#eliminarAldeas-{{$aldea->id_aldea}}">Eliminar aldea</button>

                        </div>
                      </div>
                    </th>
                  </tr>
                  <div class="modal fade" id="reportar-{{$aldea->id_aldea}}" tabindex="-1" role="dialog" aria-labelledby="reportar-{{$aldea->id_aldea}}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Reportar ataque  a la aldea "{{$aldea->nombre}}{{ __('  ') }} ({{$aldea->coord_x }}{{ __('/') }}{{$aldea->coord_y }})" </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <form action="/aldeas/reporte" method="POST">
                                    @method('PUT')
                                    @csrf
                                    <div class="modal-body">
                                        <div>
                                            <label for="madera">ATACANTE (COORD X):</label>
                                            <input type="text" name="coord_x" class="form-control" id ="coord_x"  vallue = "0" pattern="^[0-9]+">
                                        
                                            <label for="madera">ATACANTE (COORD Y):</label>
                                            <input type="text" name="coord_y" class="form-control" id ="coord_y" vallue = "0" pattern="^[0-9]+">
                                        </div>
                                        <div>
                                            <label for="aldeas_interes">Día impacto </label>  
                                            <input type="date" name="dia" >
                                            <label for="aldeas_interes">Hora impacto </label>  
                                            <input type="time" name="hora" step="1">
                                        </div>
                                        <div>
                                            <label for="aldeas_interes">Artefacto en tu poder: </label>  
                                            <select  name="tipo" class="form-control" id ="artedeff"> 
                                            </select>
                                        </div>
                                        <div>
                                            <label for="aldeas_interes">Número de ataques a la vez: </label>  
                                            <input type="text" name="Nvagones" class="form-control" vallue = "0" id ="Nvagones"  pattern="^[0-9]+">
                                        </div>
                                        <div>
                                            <label for="aldeas_interes">Posibilidad de intercalada: </label>  
                                            <select  name="intercalada" class="form-control" id ="intercalada">
                                                <option value='NO'>NO</option>
                                                <option value='SI'>SI</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="aldeas_interes">Velocidad vagones: </label>  
                                            <input type="text" name="velocidadVagones" vallue = "0" class="form-control" id ="velocidadVagones"  pattern="^[0-9]+">
                                        </div>
                                        <div>
                                            <label for="aldeas_interes">PT agresor:  </label>  
                                            <input type="text" name="ptAgresor" vallue = "0" class="form-control" id ="ptAgresor"  pattern="^[0-9]+">
                                        </div>
                                        <div>
                                            <label for="aldeas_interes">Artefacto agresor:  </label>  
                                            <select  name="arteoff" class="form-control" id ="arteoff">
                                                <option value='1'>Sin artefacto</option>
                                               
                                            </select>
                                        </div>
                                        <div>
                                            <label for="aldeas_interes">link heroe</label>  
                                            <input type="text" name="link_heroe" class="form-control" id ="link_heroe"  required>
                                        </div>
                                        <div>
                                            <label for="aldeas_interes">Artículo heroe</label>  
                                            <select  name="heroe_botas" class="form-control" id ="heroe_botas">
                                                <option value='0'>Sin botas</option>
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <input  name="idAldea" type="hidden" value="{{$aldea->id_aldea}}">
                                    <div class="modal-footer">
                                        <button type="button"class="btn btn-danger" data-dismiss="modal" aria-label="Close">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Reportar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="eliminarAldeas-{{$aldea->id_aldea}}" tabindex="-1" role="dialog" aria-labelledby="eliminarAldeas-{{$aldea->id_aldea}}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Eliminar aldea</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <form action="/Aldeas/borrar" method="POST">
                                    @method('PUT')
                                    @csrf
                                    <div class="modal-body">
                                        ¿Está seguro que desea eliminar la aldea "{{ $aldea->nombre}}"?
                                    </div>
                                    <input  name="idAldea" type="hidden" value="{{$aldea->id_aldea}}">
                                    <div class="modal-footer">
                                        <button type="button"class="btn btn-primary" data-dismiss="modal" aria-label="Close">Cancelar</button>
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editarAldea-{{$aldea->id_aldea}}" tabindex="-1" role="dialog" aria-labelledby="editarAldea-{{$aldea->id_aldea}}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Editar aldea {{$aldea->tipo}}/{{$tipo->valor}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <form action="/Aldeas/editar" action="{{'submit'}}" method="post">
                                    @method('PUT')
                                    @csrf
                                        <label for="nombreAldea">NombreAldea</libel>
                                        <input type="text" name="nombreAldea" class="form-control" id ="nombreAldea" value = "{{$aldea->nombre}}">
                                        <label for="tipo">Tipo de aldea</libel>
                                        <select  name="tipo" class="form-control" id ="tipo">
                                          @foreach($tipos as $tipo)
                                          @php           
                                            if($aldea->tipo==$tipo->valor){
                                                echo "<option value='$tipo->valor'  selected = 'selected'> $tipo->nombre</option>";
                                              }else{
                                                 echo "<option value='$tipo->valor'> $tipo->nombre</option>";
                                            }
                                          @endphp
                                            
                                          @endforeach 
                                        </select>
                                         <label for="madera">Producción de madera</libel>
                                        <input type="number" name="madera" class="form-control" id ="madera" min="1" pattern="^[0-9]+" value = "{{(int)$aldea->madera}}">
                                        <label for="barro">Producción de barro</libel>
                                        <input type="number" name="barro" class="form-control" id ="barro"  min="1" pattern="^[0-9]+" value = "{{(int)$aldea->barro}}">
                                        <label for="hierro">Producción de hierro</libel>
                                        <input type="number" name="hierro" class="form-control" id ="hierro" min="1" pattern="^[0-9]+" value = "{{(int)$aldea->hierro}}">
                                        <label for="cereal">Producción de cereal</libel>
                                        <input type="number" name="cereal" class="form-control" id ="cereal"  pattern="[-]*[0-9]+" value = "{{(int)$aldea->cereal}}">
                                        <label for="puntos_cultura">Puntos de cultura</libel>
                                        <input  name="puntos_cultura" class="form-control" id ="puntos_cultura" min="1"  pattern="^[0-9]+" value = "{{(int)$aldea->puntos_cultura}}">

                                        <label for="fiesta_grande">Fiesta grande</libel>
                                        <select  name="fiesta_grande" class="form-control" id ="fiesta_grande">
                                            @switch(true)
                                                @case($aldea->fiesta_grande == '0')
                                                    <option value="0" selected = 'selected'>No</option> 
                                                    <option value="1">Si</option> 
                                                    @break
                                                @case($aldea->fiesta_grande == '1')
                                                    <option value="0">No</option> 
                                                    <option value="1" selected = 'selected'>Si</option> 
                                                    @break
                                            @endswitch                     
                                        </select>
                                        <label for="fiesta_pequena">Fiesta pequeña</libel>
                                        <select  name="fiesta_pequena" class="form-control" id ="fiesta_pequena">
                                            @switch(true)
                                                @case($aldea->fiesta_pequena == '0')
                                                    <option value="0" selected = 'selected'>No</option> 
                                                    <option value="1">Si</option> 
                                                    @break
                                                @case($aldea->fiesta_pequena == '1')
                                                    <option value="0">No</option> 
                                                    <option value="1" selected = 'selected'>Si</option> 
                                                    @break
                                                @endswitch  
                                        </select>
                                        <input  name="idAldea" type="hidden" value="{{$aldea->id_aldea}}">
                                        <div>
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                  @endforeach
              </tbody>
              <tfoot>
            <tr>
            <th></th>
            
            <th>Total:</th>
            <th>{{$suma_madera_total}}</th>
            <th>{{$suma_barro_total}}</th>
            <th>{{$suma_hierro_total}}</th>
            <th>{{$suma_cereal_total}}</th>
            <th>{{$suma_materias_total}}</th>
            <th>{{$suma_puntosc_total}}</th>
            <th>{{$pc_totales_alianza}}</th>
            <th></th>
            <th></th>
            <th>{{$pc_totales_fiestas}}</th>
            <th>{{$pc_todos}}</th>
            <th></th>
        
        </tr>
        </tfoot>
            </table>
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
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    
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