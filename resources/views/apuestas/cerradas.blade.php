@extends('adminlte::page')

@section('title', 'Apuestas')

@section('content_header')
    <h1 class="abs-center" > </h1>
@stop

@section('content')
            <!-- /.card-header -->
        <div class="card ">
            <div class="container w-auto">
              <div class="col margin">
                <div class="text-center mb-7"> 
                  <div class= "m-3">
                    <h1>Apuestas cerradas</h1>
                  </div>
              </div>
              <div class="col  table-responsive">
                 
                   <table id="example1" class="table table-bordered table-striped ">
                    <thead class="table-dark">
                      <tr>
                        <th>Stack</th>
                        <th>Deporte</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Porcentaje</th>
                        <th>Apuesta</th>
                        <th>Probabilidad</th>
                        <th>Estado</th>
                        <th>Total €</th>
                       </tr>
                    </thead>
                    <tbody>
                      @foreach($apuestas as $apuesta)
                      <?php
                        if($apuesta->resultadoDinero<0) 
                        {
                            $class = "table-danger";
                        }elseif ($apuesta->resultadoDinero==0){
                          $class = "";
                        }else{
                          $class = "table-success";
                        }

                       
                       ?>
                        <tr class= "<?php echo $class; ?>">
                          <th>{{$apuesta->stack}}</th>
                          <th>{{$apuesta->deporte}}</th>
                          <th>{{$apuesta->descripcion}}</th>
                          <th>{{$apuesta->created_at}}</th>
                          <th>{{$apuesta->porcentaje}}</th>
                          <th>{{$apuesta->dineroApostado}}€</th>
                          <th>{{$apuesta->probabilidad}}%</th>
                          <th>{{$apuesta->estado_descri}}</th>
                          <th>{{$apuesta->resultadodinero}}</th>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
               </div>

            </div>
          </div>
              <!-- /.card-body -->
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