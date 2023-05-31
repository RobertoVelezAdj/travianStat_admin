@extends('adminlte::page')

@section('title', 'Mis tareas')

@section('content_header')
    <h1 class="abs-center" > </h1>
@stop

@section('content')
   <div class="card">
    <div class="card-body">
      <div class="w-100">
        <div class="col margin">
          <div class="text-center mb-7"> 
            
          <div class= "m-3">
            <h1>Mis tareas</h1>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg> 
                Nueva tarea
            </button>
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Nueva tarea</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <form action="/Aldeas/nuevaTarea" action="{{'submit'}}" method="post">
                            @method('PUT')
                             @csrf
                                
                                <label for="prioridad">Orden de la tarea</label>
                                <input type="number" name="prioridad" class="form-control" id ="prioridad" value="0" >
                                <label for="aldea">Aldea asociada</label>
                                <select  name="id_aldea" class="form-control" id ="id_aldea">
                                    @foreach($aldeas as $aldea)
                                        <option value="{{$aldea->id}}">{{$aldea->nombre}}({{$aldea->coord_x }}/{{$aldea->coord_y }})</option>
                                    @endforeach
                                </select>
                                <label for="Titulo">Titulo</label>
                                <input type="text" name="titulo" class="form-control" id ="titulo"  >
                                <label for="Descripcion">Descripción</label>
                                <input type="text" name="Descripcion" class="form-control" id ="Descripcion" >
                                <div>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                        </form>
                     </div>
                </div>
            </div>
         </div>
</div> 
          </div>
              @php
                $nombreAldea = "";
                $contador = 0;
              @endphp
               
                @foreach($tareas as $tarea)

                @php
                $contador++;
                if($contador>1&&$tarea->nombre!=$nombreAldea){
                 echo " </tbody> 
                    </table> 
                  </div>  ";  
                }
                  if($tarea->nombre!=$nombreAldea){
                    $nombreAldea= $tarea->nombre;
                    echo "
                    <div class= 'm-3'><h4>".$nombreAldea." (".$tarea->coord_x."/".$tarea->coord_y.")</h4></div><div class='col table-responsive'>
                    <table id='example".$contador."' class='table table-bordered table-striped'>
                      <thead class='table-dark'>
                      <tr>
                        <th>Aldea</th> 
                        <th>Orden</th> 
                        <th>Título</th> 
                        <th>Descripción</th> 
                        <th>Acciones</th> 
                      </tr> 
                       </thead>
                      <tbody>
                      ";
                  }
                @endphp        

                      <tr>
                        <th>{{$tarea->nombre}} ({{$tarea->coord_x}}/{{$tarea->coord_y}} )</th> 
                        <th>{{$tarea->prioridad}} </th> 
                        <th>{{$tarea->titulo}} </th> 
                        <th>{{$tarea->descripcion}} </th> 
                        
                        <th>
                          
                        
                        <div class="btn-group  ">
                          <button type="button" class="btn   btn-info btn-info">Acciones</button>
                          <button type="button " class="btn   dropdown-toggle dropdown-icon btn-info" data-toggle="dropdown">
                          <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                          <button class="dropdown-item btn-info" data-toggle="modal" data-target="#editar-{{$tarea->id_tarea}}">Editar tarea</button>
                          <button class="dropdown-item btn-info" data-toggle="modal" data-target="#completar-{{$tarea->id_tarea}}">Completar tarea</button>

                        </div>
                       
                        </th> 
                      </tr>  
                      <div class="modal fade" id="completar-{{$tarea->id_tarea}}" tabindex="-1" role="dialog" aria-labelledby="completar-{{$tarea->id_tarea}}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">Completar tarea<h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <form action="/Aldeas/completarTarea" method="POST">
                                                            @method('PUT')
                                                            @csrf
                                                            <div class="modal-body">
                                                                ¿Está seguro que desea completar la tarea "{{$tarea->titulo}}"?
                                                            </div>
                                                            <input  name="id_tarea" type="hidden" value="{{$tarea->id_tarea}}">
                                                            <input  name="id_aldea" type="hidden" value="{{$tarea->id_aldea}}">
                                                            <input name="prioridad" type="hidden" class="form-control" id ="prioridad" value="{{$tarea->prioridad}}" >
                                                            <div class="modal-footer">
                                                                <button type="button"class="btn btn-danger" data-dismiss="modal" aria-label="Close">Cancelar</button>
                                                                <button type="submit" class="btn btn-primary">Completar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                                 <div class="modal fade" id="editar-{{$tarea->id_tarea}}" tabindex="-1" role="dialog" aria-labelledby="editar-{{$tarea->id_tarea}}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Editar aldea</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <form action="/Aldeas/editartarea" action="{{'submit'}}" method="post">
                                                        @method('PUT')
                                                        @csrf
                                                            <label for="nombreAldea">Nueva prioridad de "{{$tarea->titulo}}":</label>
                                                            <input type="number" name="prioridad" class="form-control" id ="prioridad" value="{{$tarea->prioridad}}" >
                                                            <input  name="id_tarea" type="hidden" value="{{$tarea->id_tarea}}">
                                                            <input  name="id_aldea" type="hidden" value="{{$tarea->id_aldea}}">
                                                            <label for="Titulo">Titulo</label>
                                                          <input type="text" name="titulo" class="form-control" id ="titulo"  value="{{$tarea->titulo}}">
                                                          <label for="Descripcion">Descripción</label>
                                                          <input type="text" name="Descripcion" class="form-control" id ="Descripcion" value="{{$tarea->descripcion}}">
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
<?php
$contador =0;
foreach ($tareas as $tarea) {
  $contador++;
  echo "<script>
  $(function () {
     $('#example".$contador."').DataTable({
       'responsive': true, 'lengthChange': false, 'autoWidth': false,
       'buttons': ['copy', 'csv', 'excel', 'pdf', 'print']
     }).buttons().container().appendTo('#example".$contador."_wrapper .col-md-6:eq(0)');
   });
 </script>";
}
?> 
@stop