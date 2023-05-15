@extends('adminlte::page')

@section('title', 'dashboard')

@section('content_header')
    <h1 class="abs-center" > </h1>
@stop

@section('content')
            <!-- /.card-header -->
        <div class="card">
          <div class="card-body">
            <div class="container">
             
              <div class="col">
                <div class="text-center mb-7"> 
                <h2>Crear permisos</h2>
                <form action="/AdminPermisos/Crear" action="{{'submit'}}" method="post">
                    @method('PUT')
                    @csrf
                     <input type="text" name="nombrePermiso" class="form-control" id ="nombrePermiso"> 
                    <div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                  </form>
              </div>
              <div class="col">
                <div class="text-center"> 
                  <h2>Permisos disponibles</h2>
                </div>
                <div class="table-responsive">
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
                <div class="text-center"> 
                  <h2>USUARIOS:</h2>
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
                                <button type="button" class="btn btn-warning float-right" data-toggle="modal" data-target="#anadirP-{{$usuario['id']}}" >
                                    Añadir Permiso
                                </button>
                                <button type="button" class="btn btn-warning float-right" data-toggle="modal" data-target="#EliminarP-{{$usuario['id']}}" >
                                    Eliminar Permiso
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