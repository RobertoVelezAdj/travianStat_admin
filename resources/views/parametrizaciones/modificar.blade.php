@extends('adminlte::page')

@section('title', 'Admin parametrizaciones')

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
                    <h1>Listado parametrizacion {{$nombre}}</h1>
                  </div>
                <button type="button" class="btn btn-primary btn-lg " data-toggle="modal" data-target="#exampleModalCenter">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg> 
                Nuevo resgistro
                </button>
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Nuevo registro</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group m-3">
                            <form action="/AdminParametrizaciones/CrearRegistro" action="{{'submit'}}" method="post">
                            @method('PUT')
                             @csrf
                                <label for="nombreParam">Campo valor de la parametrizacion</label>
                                <input type="text" name="valor" class="form-control" id ="valor">
                                <label for="nombreParam">Campo nombre de la parametrizacion</label>
                                <input type="text" name="nombre" class="form-control" id ="valor">
                                <input  name="parametrizacion" type="hidden" value="{{$nombre}}">
                                <input  name="descripcion" type="hidden" value="{{$descripcion}}">  
                                <button type="submit" class="btn btn-outline-ligh bg-black m-3">Crear Parametrizacion</button>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
      </div>   

              </div>
              <div class="col table-responsive table-responsive">
                
                  <table id="example1" class="table table-bordered table-striped ">
                    <thead class="table-dark">
                      <tr>
                        <th>Nombre</th>
                        <th>Valor</th>
                        <th>Acciones</th>    
                       </tr>
                    </thead>
                    <tbody>
                      @foreach($parametrizaciones as $param)
                        <tr>
                          <th> {{$param->nombre}}  </th>
                          <th> {{$param->valor}}  </th>
                          <th> 
                            <button type="button" class="btn-app bg-danger" data-toggle="modal" data-target="#EliminarPermiso-{{$param->id}}" >
                                Eliminar Permiso
                            </button>    
                          </th>
                        </tr>
                        <div class="modal fade" id="EliminarPermiso-{{$param->id}}" tabindex="-1" role="dialog" aria-labelledby="#EliminarPermiso-{{$param->id}}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Eliminar permiso</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <form action="/AdminParametrizaciones/EliminarRegistro" method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="modal-body">
                                                    <label>¿Estas segur@ de eliminar la lista de parametrización {{$param->nombre}}/{{$param->valor}}?.</label>
                                                    <input  name="id" type="hidden" value="{{$param->id}}">  
                                                    <input  name="parametrizacion" type="hidden" value="{{$nombre}}"
                                                    <input  name="descripcion" type="hidden" value="{{$descripcion}}">  
                                                    </div>
                                                     <button type="submit" class="btn btn-outline-ligh bg-black">Eliminar permiso</button>
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