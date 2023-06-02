@extends('adminlte::page')

@section('title', 'Mis rutas de comercio')

@section('content_header')
    <h1 class="abs-center" > </h1>
@stop

@section('content')
   <div class="card">
    <div class="card-body ">
      <div class=" w-100">
        <div class="col margin   ">
          <div class="text-center mb-10 "> 
            <div class= "m-3">
              <h1>Resumen materias disponibles</h1>
            </div>
           
        <div class="col table-responsive w-auto">
              <table id="example1" class="table table-bordered table-striped ">
              <thead class="table-dark">
              <tr>
                <th>Nombre aldea</th>
                <th>Madera</th>
                <th>Barro</th>
                <th>Hierro</th>
                <th>Cereal</th>
                <th>Total materias disponibles</th>  
            </tr> 
              </thead>
              <tbody>
              @php
                $suma_madera_total = 0;
                $suma_barro_total = 0;
                $suma_hierro_total = 0;
                $suma_cereal_total = 0;
                $suma_materias_total = 0;
            
            @endphp
            @foreach($info_aldea as $aldea)
                @php
                    $suma_madera_total = $suma_madera_total+ $aldea['madera'];
                    $suma_barro_total = $suma_barro_total+ $aldea['barro'];
                    $suma_hierro_total = $suma_hierro_total + $aldea['hierro'];
                    $suma_cereal_total = $suma_cereal_total + $aldea['cereal'];
                    $total_aldea =  $aldea['madera'] + $aldea['barro'] + $aldea['hierro'] +$aldea['cereal'];
                @endphp
                <tr>
                    <td>{{$aldea['nombre_aldea']}}</td>
                    <td>{{$aldea['madera']}}</td>
                    <td>{{$aldea['barro']}}</td>
                    <td>{{$aldea['hierro']}}</td>
                    <td>{{$aldea['cereal']}}</td>
                    <td>{{$total_aldea}}</td>
                </tr>
            @endforeach
               </tbody>
               <tfoot>
        <tr>
            @php
                $suma_materias_total = $suma_madera_total+ $suma_barro_total + $suma_hierro_total +$suma_cereal_total ;
            @endphp
            <th>Total:</th>
            <th>{{$suma_madera_total}}</th>
            <th>{{$suma_barro_total}}</th>
            <th>{{$suma_hierro_total}}</th>
            <th>{{$suma_cereal_total}}</th>
            <th>{{$suma_materias_total}}</th>        
        </tr>
        </tfoot>
            </table>
        </div>
          <div class= "m-3">
            <h1>Rutas</h1>
          </div>
            <button type="button" class="btn btn-primary btn-lg " data-toggle="modal" data-target="#exampleModalCenter">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg> 
              Nueva ruta
            </button>

            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">Nueva ruta</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <div class="form-group m-3">
                          <form action="/Calculos/nuevaruta" action="{{'submit'}}" method="post">
                            @method('PUT')
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nombre aldea que envia la ruta:</label>
                                
                                <select class="form-select" aria-label="Default select example" name = "aldea_envia">
                                        @foreach($aldeas as $aldea)
                                        
                                                <option value="{{$aldea->id}}">{{$aldea->nombre}}</option>                    
                                        @endforeach
                                    </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nombre aldea que recibe la ruta:</label>
                                
                                <select class="form-select" aria-label="Default select example" name = "aldea_recibe">
                                        @foreach($aldeas as $aldea)
                                        
                                                <option value="{{$aldea->id}}">{{$aldea->nombre}}</option>                    
                                        @endforeach
                                    </select>
                            </div>
                            <div class="mb-3">
                                <label for="madera">Madera enviada</libel>
                                <input type="number" value = "0" name="madera" class="form-control" id ="madera" min="0" pattern="^[0-9]+">
                                <label for="barro">Barro enviado</libel>
                                <input type="number" value = "0" name="barro" class="form-control" id ="barro" min="0" pattern="^[0-9]+">
                                <label for="hierro">Hierro enviado</libel>
                                <input type="number" value = "0" name="hierro" class="form-control" id ="hierro" min="0" pattern="^[0-9]+">
                                <label for="cereal">Cereal enviado</libel>
                                <input type="number"  value = "0" name="cereal" class="form-control" id ="cereal" min="0" pattern="^[0-9]+">
                            </div>
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
        <div class="col table-responsive w-auto">
              <table id="example1" class="table table-bordered table-striped ">
              <thead class="table-dark">
              <tr>
                <th>Nombre aldea envia</th>
                <th>Nombre aldea recibe</th>
                <th>Madera</th>
                <th>Barro</th>
                <th>Hierro</th>
                <th>Cereal</th>
                <th>Total materias enviadas</th>
                <th>Opciones</th>    
            </tr> 
              </thead>
              <tbody>
            @php
                $suma_madera_total = 0;
                $suma_barro_total = 0;
                $suma_hierro_total = 0;
                $suma_cereal_total = 0;
                $suma_materias_total = 0;
            @endphp
            @foreach($rutas_comcercio as $ruta)
                @php
                    $suma_madera_total = $suma_madera_total+ $ruta->madera;
                    $suma_barro_total = $suma_barro_total+ $ruta->barro;
                    $suma_hierro_total = $suma_hierro_total +$ruta->hierro;
                    $suma_cereal_total = $suma_cereal_total +$ruta->cereal;
                    $total_aldea =  $ruta->madera + $ruta->barro + $ruta->hierro +$ruta->cereal;
                @endphp
                <tr>
                    <td>{{$ruta->nombreenvia}}</td>
                    <td>{{$ruta->nombre_recibe}}</td>
                    <td>{{$ruta->madera}}</td>
                    <td>{{$ruta->barro}}</td>
                    <td>{{$ruta->hierro}}</td>
                    <td>{{$ruta->cereal}}</td>
                    <td>{{$total_aldea}}</td>
                    <td>
                    <div class="margin">
                        <div class="btn-group  ">
                          <button type="button" class="btn   btn-info btn-info">Acciones</button>
                          <button type="button " class="btn   dropdown-toggle dropdown-icon btn-info" data-toggle="dropdown">
                          <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                          <button class="dropdown-item btn-info" data-toggle="modal" data-target="#editarRuta-{{$ruta->id}}">Editar aldea</button>
                          <button class="dropdown-item btn-info" data-toggle="modal" data-target="#eliminarRuta-{{$ruta->id}}">Eliminar aldea</button>

                        </div>
                      </div>
                    </td>
                </tr>
                <div class="modal fade" id="editarRuta-{{$ruta->id}}" tabindex="-1" role="dialog" aria-labelledby="editarRuta-{{$ruta->id}}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Editar ruta</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                <form action="/Calculos/editarruta" action="{{'submit'}}" method="post">
                                @method('PUT')
                                @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Nombre aldea que envia la ruta:</label>
                                        <input type="text" name="aldea_envia" class="form-control" id ="aldea_envia"  value="{{$ruta->nombreenvia}}" disabled>
                                </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nombre aldea que recibe la ruta:</label>
                                        <input type="text" name="aldea_recibe" class="form-control" id ="aldea_recibe"  value="{{$ruta->nombre_recibe}}" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label for="madera">Madera enviada</libel>
                                        <input type="text" name="madera" class="form-control" id ="madera" min="1" pattern="^[0-9]+" value="{{$ruta->madera}}">
                                        <label for="barro">Barro enviado</libel>
                                        <input type="text" name="barro" class="form-control" id ="barro" min="1" pattern="^[0-9]+"  value="{{$ruta->barro}}">
                                        <label for="hierro">Hierro enviado</libel>
                                        <input type="text" name="hierro" class="form-control" id ="hierro" min="1" pattern="^[0-9]+"  value="{{$ruta->hierro}}">
                                        <label for="cereal">Cereal enviado</libel>
                                        <input type="text" name="cereal" class="form-control" id ="cereal" min="1" pattern="^[0-9]+"  value="{{$ruta->cereal}}">
                                    </div>
                                    <input  name="id" type="hidden" value="{{$ruta->id}}">
                                    <div>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="eliminarRuta-{{$ruta->id}}" tabindex="-1" role="dialog" aria-labelledby="eliminarRuta-{{$ruta->id}}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Eliminar ruta</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                <form action="/Calculos/borrarruta" action="{{'submit'}}" method="post">
                                @method('PUT')
                                @csrf
                                ¿Está seguro que desea eliminar la ruta?
                                    <input  name="id" type="hidden" value="{{$ruta->id}}">
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