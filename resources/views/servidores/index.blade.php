@extends('adminlte::page')

@section('title', 'Admin servidores')

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
                    <h1>Servidores disponibles</h1>
                  </div>
                <button type="button" class="btn btn-primary btn-lg " data-toggle="modal" data-target="#exampleModalCenter">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg> 
                Nuevo servidor
                </button>
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Nuevo servidor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group m-3">
                            <form action="/AdminServidores/Crear" action="{{'submit'}}" method="post">
                            @method('PUT')
                             @csrf
                                <label for="nomb">Nombre del servidor</label>
                                <input type="text" name="nombre" class="form-control" id ="nombre"> 
                                <label for="nomb">Ruta</label>
                                <input type="text" name="ruta" class="form-control" id ="ruta"> 
                                <label for="nomb">Ruta inactiva</label>
                                <input type="text" name="ruta_inac" class="form-control" id ="ruta_inac">
                                <label for="nomb">Velocidad</label>
                                <select  name="velocidad" class="form-control" id ="velocidad"> 
                                  @foreach($velocidades as $velocidad)
                                    <option value="{{$velocidad->velocidad}}" > {{$velocidad->velocidad}}</option>
                                  @endforeach 
                                </select>
                                <button type="submit" class="btn btn-outline-ligh bg-black m-3">Crear servidor</button>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
      </div>   

              </div>
              <div class="col table-responsive">
                sad
                <table id="topciones" class="table table-bordered table-striped table-responsivelg">
                    <thead  class="table-dark">
                      <tr>
                        <th>ID</th>
                        <th>nombre</th>
                        <th>ruta</th>
                        <th>ruta_inac</th>
                        <th>fch_creac</th>
                        <th>created_at</th>
                        <th>updated_at</th>
                        <th>fch_mod</th>
                        <th>velocidad</th>
                        <th>Estado (Activo/Desactivado)</th>
                        <th>Opciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($servidores as $servidor)
                        <tr>
                          <th>{{$servidor->id}}</th>
                          <th>{{$servidor->nombre}}</th>
                          <th>{{$servidor->ruta}}</th>
                          <th>{{$servidor->ruta_inac}}</th>
                          <th>{{$servidor->fch_creac}}</th>
                          <th>{{$servidor->created_at}}</th>
                          <th>{{$servidor->updated_at}}</th>
                          <th>{{$servidor->fch_mod}}</th>
                          <th>{{$servidor->velocidad}}</th>
                          <th>{{$servidor->estado}}</th>
                          <th> 
                            <form action="/AdminServidores/borrar" action="{{'submit'}}" method="post">
                              @method('PUT')
                              @csrf
                              <input  name="id" type="hidden" value="{{$servidor->id}}">
                              <button type="submit" class="btn btn-outline-ligh bg-danger m-3">Borrar servidor</button>
                            </form>
                          </th>
                        </tr>
                     @endforeach
                    </tbody>  
                  </table>
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
  $(function () {
    $("#topciones").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#topciones_wrapper .col-md-6:eq(0)');
    
  });
  
</script>
@stop