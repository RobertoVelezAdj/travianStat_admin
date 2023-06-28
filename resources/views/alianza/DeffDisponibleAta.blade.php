@extends('adminlte::page')

@section('title', 'Apuestas')

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
              <h1>Push pendientes</h1>
               
        </div>
        <div class= "m-3">
              <h2>Aleas romanas</h2>
               
        </div>
        <div class="col table-responsive">
              <table id="example1" class="table table-bordered table-striped ">
              <thead class="table-dark">
                <tr>
                  <th>Cuenta</th>
                  <th>Aldea</th>
                  <th>PT</th>
                  @foreach($tropas_galas as $tropas)
                  <th>{{@tropas->nombre_tropa}}</th>
                  @endforeach
                  <th>Tiempo llegada</th>
                  <th>Tiempo restante para enviar</th> 
                  <th>Opciones</th>       
                </tr>
              </thead>
              <tbody>
              @foreach($aldeas_galas as $aldea)
              <tr>
                  <th>Cuenta</th>
                  <th>Aldea</th>
                  <th>PT</th>
                  <th>Tropa 1</th>
                  <th>Tropa 2</th>
                  <th>Tropa 3</th>
                  <th>Tropa 4</th>
                  <th>Tropa 5</th>
                  <th>Tropa 6</th>
                  <th>Tropa 7</th>
                  <th>Tropa 8</th>
                  <th>Tropa 9</th>
                  <th>Tropa 10</th>
                  <th>Tropa 11</th>
                  <th>Tiempo llegada</th>
                  <th>Tiempo restante para enviar</th> 
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