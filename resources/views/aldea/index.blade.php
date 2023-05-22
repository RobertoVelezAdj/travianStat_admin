@extends('adminlte::page')

@section('title', 'Apuestas')

@section('content_header')
    <h1 class="abs-center" > </h1>
@stop

@section('content')
   <div class="card">
    <div class="card-body">
      <div class="container">
        <div class="col margin">
          <div class="text-center mb-7"> 
            <div class= "m-3">
              <h1>Aldeas</h1>
            </div>
            <button type="button" class="btn btn-primary btn-lg " data-toggle="modal" data-target="#exampleModalCenter">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg> 
              Nueva aldea
            </button>
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
 
                                <div class="form-check form-switch">
                                  <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" checked>
                                  <label class="form-check-label" for="flexSwitchCheckDefault">Generación automática de tareas:</label>
                                </div>
                                <button type="submit" class="btn btn-outline-ligh bg-black m-3">Crear aldea</button>
                            </form>
                          </div>
                      </div>
                    </div>
                </div>
            </div>
          </div>   
        </div>
        <div class="col table-responsive">
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
                $suma_materias_aldea = $aldea->madera+$aldea->barro+$aldea->hierro+$aldea->cereal;
                $suma_madera_total =  $suma_madera_total + $aldea->madera;
                $suma_barro_total = $suma_barro_total+$aldea->barro;
                $suma_hierro_total =  $suma_hierro_total+ $aldea->hierro;
                $suma_cereal_total = $suma_cereal_total+$aldea->cereal;
                $suma_materias_total = $suma_materias_total + $suma_materias_aldea;
                /*
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
               
                $pc_totales_alianza = $pc_totales_alianza  + $pc_alianza;*/
            @endphp
                  <tr>
                    <th>{{$aldea->nombre}} ({{$aldea->coord_x}}/{{$aldea->coord_y}})</th>
                    <th>{{$aldea->tipo}}</th>
                    <th>{{$aldea->madera}}</th>
                    <th>{{$aldea->barro}}</th>
                    <th>{{$aldea->hierro}}</th>
                    <th>{{$aldea->cereal}}</th>
                    <th>{{$suma_materias_aldea}} </th>
                    <th>{{$aldea->puntos_cultura}}</th>
                    <th>PC bono alianza</th>
                    <th>Fiesta pequeña</th>
                    <th>Fiesta grande</th>
                    <th>PC generado fiestas</th>
                    <th>PC totales</th>
                    <th>Opciones</th>
                  </tr>
                  @endforeach
              </tbody>
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