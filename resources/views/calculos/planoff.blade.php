@extends('adminlte::page')

@section('title', 'Apuestas')

@section('content_header')
    <h1 class="abs-center" > </h1>
@stop

@section('content')
   <div class="card">
    <div class="card-body">
      <div class="w-100">
        <div class="col margin">
          <div class="text-center mb-7"> 
            <div class= "m-3">
              <h1>Plan ofensivo</h1>
              <button type="button" class="btn btn-primary btn-lg " data-toggle="modal" data-target="#exampleModalCenter">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg> 
              Nuevo ataque
            </button>

            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">Nueva aldea</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <div class="form-group m-3">
                            <form action="/Planofensivo/nuevoAtaque" action="{{'submit'}}" method="post">
                              @method('PUT')
                              @csrf
                                 
                                <label for="nombreParam">Tipo de aldea</label>
                                <label for="nombreAldea">Aldea donde se lanzará</label>
                                <select class="form-control"  name = "idAldea">
                                    @foreach($aldeas as $aldea)
                                            <option value="{{$aldea->id_aldea}}">{{$aldea->nombre}} ({{$aldea->coord_x}}/{{$aldea->coord_y}})</option>                    
                                    @endforeach
                                </select>
                                <label for="tipo">Tipo de tropa más lenta</label>
                                <select class="form-control"  name = "idtropa">
                                    @foreach($tipo_tropas as $tropa)
                                            <option value="{{$tropa->id}}">{{$tropa->nombre_tropa}}</option>                    
                                    @endforeach
                                </select>
                                <div>
                                            <label for="aldeas_interes">Día impacto </label>  
                                            <input type="date" name="dia" >
                                            <label for="aldeas_interes">Hora impacto </label>  
                                            <input type="time" name="hora" step="1">
                                        </div>
                                <label for="nombreParam">Coordenada destino x</label>
                                <input type="number" name="coord_x" class="form-control" id ="coor_x"   value = "0" pattern="[-]*[0-9]+"required>
                                <label for="nombreParam">Coordenada  destino y</label>
                                <input type="number" name="coord_y" class="form-control" id ="coor_y" value = "0" pattern="[-]*[0-9]+" required>
                                
                                
                               
                                 
                                <button type="submit" class="btn btn-outline-ligh bg-black m-3">Crear ataque</button>
                            </form>
                          </div>
                      </div>
                    </div>
                </div>
            </div>
          </div>   
        </div>
            </div>
            
        <div class="col table-responsive">
              <table id="example1" class="table table-bordered table-striped ">
              <thead class="table-dark">
                <tr>
                   <th>Aldea lanzamiento</th>
                   <th>Aldea destino</th>
                   <th>Distancia</th>
                   <th>Tropa más lenta</th>
                   <th>Hora lanzamiento</th>
                   <th>Hora llegada</th>
                   <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  @foreach($ataque as $a)
                    <th>{{$a->nombreLanza}} ({{$a->xlanza}}/{{$a->ylanza}})</th>
                    <th>{{$a->nombreRecibe}} ({{$a->xRecibe}}/{{$a->yRecibe}})</th>
                    <th>{{$a->distancia}}</th>
                    <th>{{$a->nombre_tropa}}</th>
                    <th>{{$a->fecha_llegada}}</th>
                    <th>{{$a->fecha_lanzamiento}}</th>
                    <th>Opciones</th>
                   @endforeach
                </tr>
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