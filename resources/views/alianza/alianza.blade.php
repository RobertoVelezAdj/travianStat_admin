@extends('adminlte::page')

@section('title', 'Admin Permisos')

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
                    <div class="col table-responsive">
                        <h1>Permisos disponibles</h1>
                    </div>
                 </div>
                
            
                
                <table id="example1" class="table table-bordered table-striped ">
                   <thead>
                    <tr>
                        <th>name</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($permisos as $permiso)
                        <tr>
                            <th> {{$permiso->valor}}</th>
                        </tr>   
                        @endforeach
                    </tbody>
                    <tfoot>
                    </tfoot> 
                </table>

                <div class= "m-3">
                    <div class="col table-responsive">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                    </svg> 
                                Añadir cuenta
                            </button>

                            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Nuevo permiso</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <form action="/datosalianza/anadirUsu" action="{{'submit'}}" method="post">
                                            @method('PUT')
                                            @csrf
                                                <label for="nombreAldea">Correo de la cuenta:</libel>
                                                <input type="text" name="correo" class="form-control" id ="correo"> 
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
                 </div>
                
                
                <table id="example1" class="table table-bordered table-striped ">
                   <thead>
                        <tr>
                            <th>email</th>
                            <th>login</th>
                            <th>Permisos</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                            <tr>
                                <th>{{$usuario->email}}</th>
                                <th>{{$usuario->login}}</th>
                                <th>@php foreach($permisos as $permiso){
                                        if($usuario->hasPermissionTo($permiso->valor)==1){
                                        echo $permiso->valor." ";
                                        }
                                        } @endphp
                                </th>
                                <th>
                                <div class="margin">
                                    <div class="btn-group  ">
                                    <button type="button" class="btn   btn-info btn-info">Acciones</button>
                                    <button type="button " class="btn   dropdown-toggle dropdown-icon btn-info" data-toggle="dropdown">
                                    <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                    <button class="dropdown-item btn-info" data-toggle="modal" data-target="#anadirP-{{$usuario->id}}">Añadir Permiso</button>
                                    <button class="dropdown-item btn-info" data-toggle="modal" data-target="#EliminarP-{{$usuario->id}}">Eliminar Permiso</button>
                                    <button class="dropdown-item btn-info" data-toggle="modal" data-target="#EliminarM-{{$usuario->id}}">Eliminar miembro de la alianza</button>
            
                                    </div>
                                </div>
                                    
                                 
                                
                                </th>
                                <div class="modal fade" id="anadirP-{{$usuario->id}}" tabindex="-1" role="dialog" aria-labelledby="#anadirP-{{$usuario->id}}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Añadir permiso</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <form action="/datosalianza/AnadirPermiso" method="POST">
                                                        @method('PUT')
                                                        @csrf
                                                        <div class="modal-body">
                                                            <select  name="permiso" class="form-control" id ="permiso">
                                                                @foreach($permisos as $permiso)
                                                                <option value=" {{$permiso->valor}}" selected = ''> {{$permiso->valor}}</option>
                                                                @endforeach
                                                            
                                                            </select>
                                                        </div>
                                                        <input  name="idUsu" type="hidden" value="{{$usuario->id}}">
                                                        <div class="modal-footer">
                                                            <button type="button"class="btn btn-primary" data-dismiss="modal" aria-label="Close">Cancelar</button>
                                                            <button type="submit" class="btn btn-danger">Añadir permiso</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="EliminarP-{{$usuario->id}}" tabindex="-1" role="dialog" aria-labelledby="#EliminarP-{{$usuario->id}}" aria-hidden="true">
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
                                                    <form action="/datosalianza/EliminarPermiso" method="POST">
                                                        @method('PUT')
                                                        @csrf
                                                        <div class="modal-body">
                                                            <select  name="permiso" class="form-control" id ="permiso">
                                                        
                                                                @foreach($permisos as $permiso)
                                                                <option value=" {{$permiso->valor}}" selected = ''> {{$permiso->valor}}</option>
                                                                @endforeach
                                                            
                                                            </select>
                                                        </div>
                                                        <input  name="idUsu" type="hidden" value="{{$usuario->id}}">
                                                        <div class="modal-footer">
                                                            <button type="button"class="btn btn-primary" data-dismiss="modal" aria-label="Close">Cancelar</button>
                                                            <button type="submit" class="btn btn-danger">Eliminar permiso</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="EliminarM-{{$usuario->id}}" tabindex="-1" role="dialog" aria-labelledby="#EliminarM-{{$usuario->id}}" aria-hidden="true">
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
                                                    <form action="/datosalianza/dejarAli2" method="POST">
                                                        @method('PUT')
                                                        @csrf
                                                        <div class="modal-body">
                                                            ¿Está seguro que quiere eliminar a de la alianza "{{$usuario->email}}" {{$usuario->login}} ""?
                                                        </div>
                                                        <input  name="idUsu" type="hidden" value="{{$usuario->id}}">
                                                        <div class="modal-footer">
                                                            <button type="button"class="btn btn-primary" data-dismiss="modal" aria-label="Close">Cancelar</button>
                                                            <button type="submit" class="btn btn-danger">Eliminar permiso</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>   
                            @endforeach
                    </tbody>
                    <tfoot>
                    </tfoot> 
                </table>

                <div class= "m-3">
                    <div class="col table-responsive">
                        <h1>Invitaciones pendientes</h1>
                    </div>
                 </div>
                 
                 <table id="example1" class="table table-bordered table-striped ">
                   <thead>
                    <tr>
                        <th>email</th>
                        <th>Fecha</th>
                        <th>Opciones</th>
                    </tr>
                    </thead>
                    <tbody>
                     @foreach($pendientes as $usuario)
                        <tr>
                            <th>{{$usuario->email}}</th>
                            <th>{{$usuario->fecha}}</th>
                            <th><button type="button" class="btn btn-warning float-right" data-toggle="modal" data-target="#EliminarI-{{$usuario->id}}" >
                                    Eliminar invitación
                                </button></th>
                            <div class="modal fade" id="EliminarI-{{$usuario->id}}" tabindex="-1" role="dialog" aria-labelledby="#EliminarI-{{$usuario->id}}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Eliminar invitación</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <form action="/datosalianza/eliminarPeticion" method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="modal-body">
                                                        ¿Está seguro que desea eliminar la invitación?
                                                    </div>
                                                    <input  name="id" type="hidden" value="{{$usuario->id}}">
                                                    <div class="modal-footer">
                                                        <button type="button"class="btn btn-primary" data-dismiss="modal" aria-label="Close">Cancelar</button>
                                                        <button type="submit" class="btn btn-danger">Eliminar invitación</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tr>   
                        @endforeach
                    </tbody>
                    <tfoot>
                    </tfoot> 
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
  $(function () {
    $("#topciones").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#topciones_wrapper .col-md-6:eq(0)');
    
  });
  
</script>
@stop