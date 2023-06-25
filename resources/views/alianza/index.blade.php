<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <div>{{ __('Administración de usuarios  ') }}</div>
        </h2>
      
    </x-slot>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.5.1.js" ></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js" ></script>
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js" ></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js" ></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js" ></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" ></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.colVis.min.js" ></script>

<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

    <div class="py-12">

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class=" bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="w-75 p-3 mx-auto sm:px-6 lg:px-8 text-center ">
        <h1>Permisos</h1>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
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
                        <div class="form-group">
                            <form action="/adminUsuarios/crearPermiso" action="{{'submit'}}" method="post">
                            @method('PUT')
                             @csrf
                                <label for="nombreAldea">Nombre del permiso</libel>
                                <input type="text" name="nombrePermiso" class="form-control" id ="nombrePermiso"> 
                                <div>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
      </div>   

                <div class="card-body">
                    <table id="topciones" class="table table-striped  table-bordered table-hover table-responsive  ">
                        <thead>
                        <tr>
                            <th>name</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($permisos as $permiso)
                            <tr>
                              <th> {{$permiso}}</th>
                            </tr>   
                            @endforeach
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                   

            </div>
        
      <h1>Usuarios</h1> 

                <div class="card-body">
                    <table id="topciones" class="table table-striped  table-bordered table-hover table-responsive">
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
                                                    <form action="/adminUsuarios/AnadirPermiso" method="POST">
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
                                                    <form action="/adminUsuarios/EliminarPermiso" method="POST">
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
                        <tfoot>
                        </tfoot>
                    </table>
                   

            </div>
        </div>
    </div>
</x-app-layout>
