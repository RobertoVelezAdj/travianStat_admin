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
                    <h1>Crear permisos</h1>
                  </div>
                <button type="button" class="btn btn-primary btn-lg " data-toggle="modal" data-target="#exampleModalCenter">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg> 
                Nuevo permiso
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
                        <div class="form-group m-3">
                            <form action="/AdminPermisos/Crear" action="{{'submit'}}" method="post">
                            @method('PUT')
                             @csrf
                                <label for="nombreAldea">Nombre del permiso</libel>
                                <input type="text" name="nombrePermiso" class="form-control" id ="nombrePermiso"> 
                                <div  class="m-3">
                                  <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
      </div>   

              </div>
              <div class="col">
                <div class="text-center m-5"> 
                  <h1>Permisos disponibles</h1>
                </div>
                <div class="table-responsive m-3">
                  <table id="example1" class="table table-bordered table-striped ">
                    <thead>
                      <tr>
                        <th>Permisos</th>  
                       </tr>
                    </thead>
                    <tbody>
                      @foreach($permisos as $permiso)
                        <tr>
                          <th> {{$permiso}}</th>
                        </tr>   
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="col">
                <div class="text-center m-5"> 
                  <h1>USUARIOS:</h1>
                </div>
                <div class="table-responsive">
                  <table id="topciones" class="table table-bordered table-striped table-responsivelg">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>email</th>
                        <th>servidor</th>
                        <th>login</th>
                        <th>Permisos</th>
                        <th>Opciones</th>
                        
                        
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($usuarios as $usuario)
                        <tr>
                            <th>{{$usuario['id']}}</th>
                            <th>{{$usuario['email']}}</th>
                            <th>{{$usuario['servidor']}}</th>
                            <th>{{$usuario['login']}}</th>
                            <th>{{$usuario->getPermissionNames()}}</th>
                            <th>
                              <div class="margin">
                                <div class="btn-group  ">
                                  <button type="button" class="btn   btn-info btn-info">Acciones</button>
                                  <button type="button " class="btn   dropdown-toggle dropdown-icon btn-info" data-toggle="dropdown">
                                  <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                  <button class="dropdown-item btn-info" data-toggle="modal" data-target="#anadirP-{{$usuario['id']}}">Añadir permisos</button>
                                  <button class="dropdown-item btn-info" data-toggle="modal" data-target="#EliminarP-{{$usuario['id']}}">Eliminar permisos</button>
                                </div>
                              </div>
                              <button type="button" class="btn-app bg-danger" data-toggle="modal" data-target="#EliminarP-{{$usuario['id']}}" >
                                Eliminar Usuario
                              </button>    
                            </th>
                            <div class="modal fade" id="anadirP-{{$usuario['id']}}" tabindex="-1" role="dialog" aria-labelledby="#anadirP-{{$usuario['id']}}" aria-hidden="true">
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
                                                <form action="/AdminPermisos/Añadir" method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="modal-body">
                                                        <select  name="permiso" class="form-control" id ="permiso">
                                                            @foreach($permisos as $permiso)
                                                            <option value=" {{$permiso}}" selected = ''> {{$permiso}}</option>
                                                            @endforeach
                                                            
                                                        </select>
                                                    </div>
                                                    <input  name="idUsu" type="hidden" value="{{$usuario['id']}}">
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
                            <div class="modal fade" id="EliminarP-{{$usuario['id']}}" tabindex="-1" role="dialog" aria-labelledby="#EliminarP-{{$usuario['id']}}" aria-hidden="true">
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
                                                <form action="/AdminPermisos/Eliminar" method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="modal-body">
                                                        <select  name="permiso" class="form-control" id ="permiso">
                                                          @php 
                                                            $permi =$usuario->getPermissionNames();
                                                            print_r($permi);
                                                          @endphp
                                                      
                                                          @foreach($permi as $permiso)
                                                          <option value=" {{$permiso}}" selected = ''> {{$permiso}}</option>
                                                          @endforeach 
                                                            
                                                        </select>
                                                    </div>
                                                    <input  name="idUsu" type="hidden" value="{{$usuario['id']}}">
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
    <script> console.log('Hi!'); </script>
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
@stop