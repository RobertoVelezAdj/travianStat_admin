@extends('adminlte::page')

@section('title', 'Apuestas')

@section('content_header')
    <h1 class="abs-center" > </h1>
@stop

@section('content')
            <!-- /.card-header -->
        <div class="card">
          <div class="card-body">
            <div class="container">
              <div class="col margin">
                <div class="text-center mb-7"> 
                  <div class= "m-3">
                    <h1>Apuestas cerradas</h1>
                  </div>
              </div>
              <div class="col">
                <div class="text-center m-5"> 
                </div>
                <div class="table-responsive m-3" width="20">
                  <table id="example1" class="table table-bordered table-striped ">
                    <thead class="table-dark">
                      <tr>
                        <th>Stack</th>
                        <th>Deporte</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Apuesta</th>
                        <th>Probabilidad</th>
                       </tr>
                    </thead>
                    <tbody>
                      @foreach($apuestas as $apuesta)
                        <tr>
                          <th>{{$apuesta->stack}}</th>
                          <th>{{$apuesta->deporte}}</th>
                          <th>{{$apuesta->descripcion}}</th>
                          <th>{{$apuesta->created_at}}</th>
                           <th>{{$apuesta->dineroApostado}}€</th>
                          <th>{{$apuesta->probabilidad}}%</th>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
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
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
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