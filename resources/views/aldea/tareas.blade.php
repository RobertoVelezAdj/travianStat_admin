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
                        <th>Prioridad</th> 
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
                        
                        <th>Acciones</th> 
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