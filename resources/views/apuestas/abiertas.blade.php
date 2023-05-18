@extends('adminlte::page')

@section('title', 'Apuestas')

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
                    <h1>Apuestas abiertas</h1>
                  </div>
                <button type="button" class="btn btn-primary btn-lg " data-toggle="modal" data-target="#exampleModalCenter">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg> 
                Nueva apuesta
                </button>
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Nueva apuesta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group m-3">
                            <form action="/Apuestas/Crear" action="{{'submit'}}" method="post">
                            @method('PUT')
                             @csrf
                                <label for="nombreParam">Porcentaje apuesta</label>
                                <input type="text" name="porcentaje" class="form-control" id ="porcentaje">
                                <label for="nombreParam">Descripción</label>
                                <input type="text" name="descripcion" class="form-control" id ="descripcion">
                                <label for="nombreParam">Deporte</label>
                                <select  name="deporte" class="form-control" id ="deporte">
                                    @foreach($deportes as $deporte){
                                         <option value='{{$deporte->valor}}'>{{$deporte->nombre}}</option>
                                    @endforeach
                                    
                                </select>
                                <button type="submit" class="btn btn-outline-ligh bg-black m-3">Crear Parametrizacion</button>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
      </div>   

              </div>
              <div class="col">
                <div class="text-center m-5"> 
                </div>
                <div class="table-responsive m-3" width="20">
                  <table id="example1" class="table table-bordered table-striped ">
                    <thead class="table-dark">
                      <tr>
                        <th>Stack</th>
                        <th>Deporte</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Porcentaje</th>
                        <th>Apuesta</th>
                        <th>Probabilidad</th>
                        <th>Opciones</th>
                       </tr>
                    </thead>
                    <tbody>
                      @foreach($apuestas as $apuesta)
                        <tr>
                          <th>{{$apuesta->stack}}</th>
                          <th>{{$apuesta->deporte}}</th>
                          <th>{{$apuesta->descripcion}}</th>
                          <th>{{$apuesta->created_at}}</th>
                           <th>{{$apuesta->dineroApostado}}€</th>
                          <th>{{$apuesta->probabilidad}}%</th>
                          <th> 
                            <button type="button" class="btn   btn-info btn-info" data-toggle="modal" data-target="#Resultado-{{$apuesta->id}}">Resultado</button>  
                          </th>
                        </tr>
                           <div class="modal fade" id="Resultado-{{$apuesta->id}}" tabindex="-1" role="dialog" aria-labelledby="#Resultado-{{$apuesta->id}}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Fianlizar apuesta</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <form action="/Apuestas/FinalizarApuesta" method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    @method('PUT')
                                                        @csrf
                                                        <div class="modal-body">
                                                        <select  name="resultado" class="form-control" id ="resultado">
                                                            <option value="1">Ganada</option>
                                                            <option value="2">Cierre</option> 
                                                            <option value="3">Perdida</option> 
                                                        </select>
                                                        </div>
                                                        @php 
                                                          $cierre =round(($apuesta->porcentaje/100)*$apuesta->dineroApostado,2,PHP_ROUND_HALF_UP);
                                                        @endphp
                                                        <label for="nombreAldea">Cierre</label>
                                                            <input type="text" name="cierre" class="form-control" id ="cierre" value="{{$cierre}}"> 
                                                        <input  name="idapuesta" type="hidden" value="{{$apuesta->id}}">
                                                        <input  name="porcentaje" type="hidden" value="{{$apuesta->porcentaje}}">
                                                        <input  name="apuesta" type="hidden" value="{{$apuesta->dineroApostado}}">
                                                        <div class="modal-footer">
                                                            <button type="button"class="btn btn-primary" data-dismiss="modal" aria-label="Close">Cancelar</button>
                                                            <button type="submit" class="btn btn-danger">Finalizar apuesta</button>
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
              <!-- /.card-body -->
          </div>

          
 @stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
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