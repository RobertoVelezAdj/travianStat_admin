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
                  @if($tiene_alianza =='NO')
                    <h1>Peticiones nueva alianza:</h1>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg> 
                        Nueva alianza
                    </button>
                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Nueva alianza</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <form action="/datosalianza/crearAlianza" action="{{'submit'}}" method="post">
                                            @method('PUT')
                                            @csrf
                                            <h2>
                                                Creación de alianza
                                            </h2>
                                            <label for="barro">Nombre de la alianza</label>
                                            <input type="text" name="nombre" class="form-control" id ="nombre"  value = "">
                                            <button type="submit" class="btn btn-primary">Crear alianza</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>      
                  @else
                  <h1>Datos de la alianza</h1>
                  @endif
                  </div>
                 
                </div>
                
            
                <div class="col table-responsive">
                @if($tiene_alianza =='NO')
                <table id="example1" class="table table-bordered table-striped ">
                        <thead class="table-dark">
                            <tr>
                                <th>Nombre alianza</th>
                                <th>Fecha</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peticiones as $p)
                                <tr>
                                <td>{{$p->nombre}}</td>
                                <td>{{$p->fecha}}</td>
                                <td>
                                <div class="btn-group btn-group-sm" role="group" aria-label="...">
                                    <button type="button" class="btn btn-warning float-right" data-toggle="modal" data-target="#aceptar-{{$p->id}}" >
                                    Aceptar invitación
                                    </button>  
                                    <button type="button" class="btn btn btn-danger float-right" data-toggle="modal" data-target="#eliminar-{{$p->id}}" >
                                    Eliminar invitación
                                    </button>
                                </div>
                                </td>
                                </tr>
                                <div class="modal fade" id="aceptar-{{$p->id}}" tabindex="-1" role="dialog" aria-labelledby="aceptar-{{$p->id}}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Aceptar invitación</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <form action="datosalianza/aceptarPeticion" method="POST">
                                                        @method('PUT')
                                                        @csrf
                                                        <div class="modal-body">
                                                        </div>
                                                        <input  name="id" type="hidden" value="{{$p->id}}">
                                                        <div class="modal-footer">
                                                            <button type="button"class="btn btn-primary" data-dismiss="modal" aria-label="Close">Cancelar</button>
                                                            <button type="submit" class="btn btn-danger">Aceptar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="eliminar-{{$p->id}}" tabindex="-1" role="dialog" aria-labelledby="eliminar-{{$p->id}}" aria-hidden="true">
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
                                                    <form action="/datosalianza/eliminarPeticion2" method="POST">
                                                        @method('PUT')
                                                        @csrf
                                                        <div class="modal-body">
                                                        </div>
                                                        <input  name="id" type="hidden" value="{{$p->id}}">
                                                        <div class="modal-footer">
                                                            <button type="button"class="btn btn-primary" data-dismiss="modal" aria-label="Close">Cancelar</button>
                                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    @foreach($alianza as $a)
                                  
                             @if(auth()->user()->hasPermissionTo('lider_alianza')==1)
                             <div class="text-center mb-7"> 
                                <div class= "m-3">
                                   
                                    
                                           
                                                    <div class="btn-group  ">
                                                    <button type="button" class="btn   btn-info btn-info">Acciones</button>
                                                    <button type="button " class="btn   dropdown-toggle dropdown-icon btn-info" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <div class="dropdown-menu" role="menu">
                                                    <button class="dropdown-item btn-info" data-toggle="modal" data-target="#exampleModalCenter2">Dejar Alianza</button>
                                                    <button class="dropdown-item btn-info" data-toggle="modal" data-target="#crearConfe">Crear coalición</button>
                                                    <button class="dropdown-item btn-info" data-toggle="modal" data-target="#EntrarConfe">Entrar coalición</button>
                                                    <button class="dropdown-item btn-info" data-toggle="modal" data-target="#DejarCoa">Dejar coalición</button>

                                                    
                                            
                                    </div>
                                </div>
                                            
                                  

                                       <div class="modal fade" id="exampleModalCenter2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle2" aria-hidden="true">
                                           <div class="modal-dialog modal-dialog-centered" role="document">
                                               <div class="modal-content">
                                                   <div class="modal-header">
                                                       <h5 class="modal-title" id="exampleModalLongTitle">Dejar  aldea</h5>
                                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                       <span aria-hidden="true">&times;</span>
                                                       </button>
                                                   </div>
                                                   <div class="modal-body">
                                                       <div class="form-group">
                                                           <form action="/datosalianza/dejarAli" action="{{'submit'}}" method="post">
                                                               @method('PUT')
                                                               @csrf
                                                               <h2>
                                                                   Estás seguro de dejar la alianza??
                                                               </h2>
                                                               <button type="submit" class="btn btn-danger">Salir Alianza</button>
                                                           </form>
                                                       </div>
                                                   </div>
                                               </div>
                                            </div>      
                                       </div>
                                  

                                   
                                   <div class="modal fade" id="crearConfe" tabindex="-1" role="dialog" aria-labelledby="crearConfe" aria-hidden="true">
                                           <div class="modal-dialog modal-dialog-centered" role="document">
                                               <div class="modal-content">
                                                   <div class="modal-header">
                                                       <h5 class="modal-title" id="exampleModalLongTitle">Entrar confederación</h5>
                                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                       <span aria-hidden="true">&times;</span>
                                                       </button>
                                                   </div>
                                                   <div class="modal-body">
                                                       <div class="form-group">
                                                           <form action="/datosalianza/crearConfederacion" action="{{'submit'}}" method="post">
                                                               @method('PUT')
                                                               @csrf
                                                               <h2>
                                                                   ¿Quieres crear una confederación?
                                                               </h2>
                                                               Input para poner algún valor
                                                               <button type="submit" class="btn btn-primary">Crear Alianza</button>
                                                           </form>
                                                       </div>
                                                   </div>
                                               </div>
                                            </div>      
                                       </div>
                                   </div>  
                                 
                                   <div class="modal fade" id="DejarCoa" tabindex="-1" role="dialog" aria-labelledby="DejarCoa" aria-hidden="true">
                                           <div class="modal-dialog modal-dialog-centered" role="document">
                                               <div class="modal-content">
                                                   <div class="modal-header">
                                                       <h5 class="modal-title" id="exampleModalLongTitle">Dejar confederación</h5>
                                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                       <span aria-hidden="true">&times;</span>
                                                       </button>
                                                   </div>
                                                   <div class="modal-body">
                                                       <div class="form-group">
                                                           <form action="/datosalianza/crearConfederacion" action="{{'submit'}}" method="post">
                                                               @method('PUT')
                                                               @csrf
                                                               <h2>
                                                                   ¿Quieres dejar la confederación?
                                                               </h2>
                                                               <button type="submit" class="btn btn-primary">Crear Alianza</button>
                                                           </form>
                                                       </div>
                                                   </div>
                                               </div>
                                            </div>      
                                       </div>
                                   </div>  

                                   <div class="modal fade" id="EntrarConfe" tabindex="-1" role="dialog" aria-labelledby="EntrarConfe" aria-hidden="true">
                                           <div class="modal-dialog modal-dialog-centered" role="document">
                                               <div class="modal-content">
                                                   <div class="modal-header">
                                                       <h5 class="modal-title" id="exampleModalLongTitle">Entrar confederación</h5>
                                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                       <span aria-hidden="true">&times;</span>
                                                       </button>
                                                   </div>
                                                   <div class="modal-body">
                                                       <div class="form-group">
                                                           <form action="/datosalianza/crearConfederacion" action="{{'submit'}}" method="post">
                                                               @method('PUT')
                                                               @csrf
                                                               <h2>
                                                                   ¿Quieres entrar la confederación?
                                                               </h2>
                                                               input con algo para elegir en cual entrar
                                                               <button type="submit" class="btn btn-primary">Crear Alianza</button>
                                                           </form>
                                                       </div>
                                                   </div>
                                               </div>
                                            </div>      
                                       </div>
                                      
                                       <form action="/datosalianza/editarAlianza"  action="{{'submit'}}" method="post">
                                           @method('PUT')
                                           @csrf
                                           
                                           <label for="barro">Nombre de la alianza</label>
                                           <input  name="idAli" type="hidden" value="{{$a->id}}">
                                           <input type="text" name="nombre" class="form-control" id ="nombre"  value = "{{$a->nombre}}">

                                           <label for="barro">Bono de reclutamiento</label>
                                           <select  name="reclutamiento" class="form-control" id ="reclutamiento">
                                               @foreach($reclutamiento as $m){
                                                   @if($a->reclutamiento==$m->valor)
                                                       <option value='{{$m->valor}}' selected = 'selected'>{{$m->nombre}}</option>
                                                   @else
                                                   <option value='{{$m->valor}}'>{{$m->nombre}}</option>
                                                   @endif    
                                               @endforeach    
                                           </select>   

                                           <label for="filosofia">Bono de filosofia</label>
                                           <select  name="filosofia" class="form-control" id ="filosofia">
                                               @foreach($filosofia as $m){
                                                   @if($a->filosofia==$m->valor)
                                                       <option value='{{$m->valor}}' selected = 'selected'>{{$m->nombre}}</option>
                                                   @else
                                                   <option value='{{$m->valor}}'>{{$m->nombre}}</option>
                                                   @endif    
                                               @endforeach    
                                           </select>    

                                           <label for="barro">Bono de metalurgia</label>
                                           <select  name="metalurgia" class="form-control" id ="metalurgia">
                                               @foreach($metalurgia as $m){
                                                   @if($a->metalurgia==$m->valor)
                                                       <option value='{{$m->valor}}' selected = 'selected'>{{$m->nombre}}</option>
                                                   @else
                                                   <option value='{{$m->valor}}'>{{$m->nombre}}</option>
                                                   @endif    
                                               @endforeach     
                                           </select>                       
                                                                               
                                           <label for="comercio">Bono de comercio</label>
                                           <select  name="comercio" class="form-control" id ="comercio">
                                               @foreach($comercio as $m){
                                                   @if($a->comercio==$m->valor)
                                                       <option value='{{$m->valor}}' selected = 'selected'>{{$m->nombre}}</option>
                                                   @else
                                                   <option value='{{$m->valor}}'>{{$m->nombre}}</option>
                                                   @endif    
                                               @endforeach    
                                           </select>
                                           <button type="submit"class="btn btn-primary" data-dismiss="modal" aria-label="Close">Modificar datos de la alianza</button>
                                       </form>
                                   @else

                                   <div class="text-center mb-7"> 
                                <div class= "m-3">
                                    <div class="col table-responsive">
                                    <div class="card-body "> 
                                            <div class="margin">
                                                    <div class="btn-group  ">
                                                    <button type="button" class="btn   btn-info btn-info">Acciones</button>
                                                    <button type="button " class="btn   dropdown-toggle dropdown-icon btn-info" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <div class="dropdown-menu" role="menu">
                                                    <button class="dropdown-item btn-info" data-toggle="modal" data-target="#exampleModalCenter2">Dejar Alianza</button>
                            
                                                    </div>
                                                </div>
                                    </div>
                                </div>
                                            
                                  

                                       <div class="modal fade" id="exampleModalCenter2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle2" aria-hidden="true">
                                           <div class="modal-dialog modal-dialog-centered" role="document">
                                               <div class="modal-content">
                                                   <div class="modal-header">
                                                       <h5 class="modal-title" id="exampleModalLongTitle">Dejar  aldea</h5>
                                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                       <span aria-hidden="true">&times;</span>
                                                       </button>
                                                   </div>
                                                   <div class="modal-body">
                                                       <div class="form-group">
                                                           <form action="/datosalianza/dejarAli" action="{{'submit'}}" method="post">
                                                               @method('PUT')
                                                               @csrf
                                                               <h2>
                                                                   Estás seguro de dejar la alianza??
                                                               </h2>
                                                               <button type="submit" class="btn btn-danger">Salir Alianza</button>
                                                           </form>
                                                       </div>
                                                   </div>
                                               </div>
                                            </div>      
                                       </div>
                                   </div>  
                                   <div class="modal fade" id="Push" tabindex="-1" role="dialog" aria-labelledby="Push" aria-hidden="true">
                                           <div class="modal-dialog modal-dialog-centered" role="document">
                                               <div class="modal-content">
                                                   <div class="modal-header">
                                                       <h5 class="modal-title" id="exampleModalLongTitle">Push alianza</h5>
                                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                       <span aria-hidden="true">&times;</span>
                                                       </button>
                                                   </div>
                                                   <div class="modal-body">
                                                       <div class="form-group">
                                                           <form action="/datosalianza/dejarAli" action="{{'submit'}}" method="post">
                                                               @method('PUT')
                                                               @csrf
                                                               <label for="nombreParam">Coordenada x</label>
                                                                <input type="number" name="coor_x" class="form-control" id ="coor_x"   value = "0" pattern="[-]*[0-9]+"required>
                                                                <label for="nombreParam">Coordenada y</label>
                                                                <input type="number" name="coor_y" class="form-control" id ="coor_y" value = "0" pattern="[-]*[0-9]+" required>
                                                               <button type="submit" class="btn btn-danger">Realizar push</button>
                                                           </form>
                                                       </div>
                                                   </div>
                                               </div>
                                            </div>      
                                       </div>
                                   </div>  
                                           <label for="barro">Nombre de la alianza</label>
                                           <input type="text" name="nombre" class="form-control" id ="nombre"  value = "{{$a->nombre}}" disabled>
                                           
                                           <label for="reclutamiento">Bono de reclutamiento</label>
                                           <select  name="reclutamiento" class="form-control" id ="reclutamiento" disabled>
                                               @foreach($reclutamiento as $m){
                                                   @if($a->reclutamiento==$m->valor)
                                                       <option value='{{$m->valor}}' selected = 'selected'>{{$m->nombre}}</option>
                                                   @else
                                                   <option value='{{$m->valor}}'>{{$m->nombre}}</option>
                                                   @endif    
                                               @endforeach    
                                           </select>   

                                           <label for="filosofia">Bono de filosofia</label>
                                           <select  name="filosofia" class="form-control" id ="filosofia"disabled> 
                                               @foreach($filosofia as $m){
                                                   @if($a->filosofia==$m->valor)
                                                       <option value='{{$m->valor}}' selected = 'selected' disabled>{{$m->nombre}}</option>
                                                   @else
                                                   <option value='{{$m->valor}}'>{{$m->nombre}}</option>
                                                   @endif    
                                               @endforeach    
                                           </select>    


                                           <label for="barro">Bono de metalurgia</label>
                                           <select  name="metalurgia" class="form-control" id ="metalurgia"disabled>
                                               @foreach($metalurgia as $m){
                                                   @if($a->metalurgia==$m->valor)
                                                       <option value='{{$m->valor}}' selected = 'selected' >{{$m->nombre}}</option>
                                                   @else
                                                   <option value='{{$m->valor}}'>{{$m->nombre}}</option>
                                                   @endif    
                                               @endforeach     
                                           </select>                             

                                           <label for="comercio">Bono de comercio</label>
                                           <select  name="comercio" class="form-control" id ="comercio" disabled>
                                               @foreach($comercio as $m){
                                                   @if($a->comercio==$m->valor)
                                                       <option value='{{$m->valor}}' selected = 'selected'>{{$m->nombre}}</option>
                                                   @else
                                                   <option value='{{$m->valor}}'>{{$m->nombre}}</option>
                                                   @endif    
                                               @endforeach    
                                           </select>                            
                                       @endif
                           @endforeach
                  @endif
                    
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