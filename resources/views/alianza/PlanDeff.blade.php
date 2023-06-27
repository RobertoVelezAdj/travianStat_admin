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
       
        <div class="col table-responsive">
              <table id="example1" class="table table-bordered table-striped ">
              <thead class="table-dark">
                <tr>
                  <th>Cuenta atacada</th>
                  <th>Aldea atacada </th>
                  <th>Tipo aldea</th>
                  <th>Cuenta atacante</th>
                  <th>Aldea atacante</th>
                  <th>Alianza atacante</th> 
                  <th>Cambio Heroe </th> 
                  <th>Posibilidad de intercalada</th> 
                  <th>Fecha llegada ataque</th> 
                  <th>Fecha visto ataque</th> 
                  <th>Vagones</th>
                  <th>¿Posibles catas?
                  </th>  
                  <th>Opciones</th>       
                </tr>
              </thead>
              <tbody>
              @foreach($aldeas as $aldea)
              <tr>
                  <th>{{$aldea->n_cuenta_atacada}} </th>
                  <th>{{$aldea->aldea_atacada}} </th>
                  <th>{{$aldea->tipo}} </th>
                  <th>{{$aldea->nombreCuentaLanza}}</th>
                  <th>{{$aldea->aldea_atante}}</th>
                  <th>{{$aldea->nombreAlianza}}</th> 
                  <th>@if($aldea->visto>$aldea->fecha_cambio)
                    {{__('NO')}}
                    @else
                      {{__('SI')}}
                    @endif
                  </th>
                  <th>{{$aldea->intercalada}}</th>
                  <th>{{$aldea->llegada}}</th>
                  <th>{{$aldea->visto}}</th>
                  <th>{{$aldea->vagones}}</th>
                  <th>@if($aldea->catas>0)
                    {{__('SI')}}
                    @else
                      {{__('NO')}}
                    @endif
                  </th>
                  <th> 
                      <div class="margin">
                        <div class="btn-group  ">
                          <button type="button" class="btn   btn-info btn-info">Acciones</button>
                          <button type="button " class="btn   dropdown-toggle dropdown-icon btn-info" data-toggle="dropdown">
                          <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                          <button class="dropdown-item btn-info" data-toggle="modal" data-target="#editarAldea-{{$aldea->id_aldea}}">Defensas disponibles</button> 
                        </div>
                      </div>
                    </th>
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