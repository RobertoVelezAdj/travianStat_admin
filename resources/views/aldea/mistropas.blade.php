@extends('adminlte::page')

@section('title', 'Mis tropas')

@section('content_header')
    <h1 class="abs-center" > </h1>
@stop

@section('content')
   <div class="card">
    <div class="card-body">
      <div class="container  w-auto">
        <div class="col margin  w-10">
          <div class="text-center mb-10  w-10"> 
            <div class= "m-3">
              <h1>Aldeas</h1>
            </div>
            <button type="button" class="btn btn-primary btn-lg " data-toggle="modal" data-target="#exampleModalCenter">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg> 
              Actualizar tropas
            </button>
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">Tropas</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <div class="form-group m-3">
                            <form action="/Aldeas/actualizar" action="{{'submit'}}" method="post">
                              @method('PUT')
                              @csrf
                              <input type="text" name="madera" class="form-control" id ="tropas"  >
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
                  <th>Aldea</th>
                  @foreach($tipo_tropas as $tropa)
                  <th>{{$tropa->nombre_tropa}}</th>
                  @endforeach
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
              @foreach($tropas as $tropa)
              <tr>
                <th>{{$tropa->nombre}}({{$tropa->coord_x}}/{{$tropa->coord_y}})</th>
                <th>{{$tropa->tropa_1}}</th>
                <th>{{$tropa->tropa_2}}</th>
                <th>{{$tropa->tropa_3}}</th>
                <th>{{$tropa->tropa_4}}</th>
                <th>{{$tropa->tropa_5}}</th>
                <th>{{$tropa->tropa_6}}</th>
                <th>{{$tropa->tropa_7}}</th>
                <th>{{$tropa->tropa_8}}</th>
                <th>{{$tropa->tropa_9}}</th>
                <th>{{$tropa->tropa_10}}</th>
                     <th> 
                      <div class="margin">
                        <div class="btn-group  ">
                          <button type="button" class="btn   btn-info btn-info">Acciones</button>
                          <button type="button " class="btn   dropdown-toggle dropdown-icon btn-info" data-toggle="dropdown">
                          <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                          <button class="dropdown-item btn-info" data-toggle="modal" data-target="#editarAldea-">Editar aldea</button>
                          <button class="dropdown-item btn-info" data-toggle="modal" data-target="#eliminarAldeas-">Eliminar aldea</button>

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