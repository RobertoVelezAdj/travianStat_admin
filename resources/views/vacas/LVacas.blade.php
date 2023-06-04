@extends('adminlte::page')

@section('title', 'Lista de vacas')

@section('content_header')
    <h1 class="abs-center" > </h1>
@stop

@section('content')
   <div class="card">
    <div class="card-body">
      <div class="   w-auto">
        <div class="col margin  w-10">
          <div class="text-center mb-10  w-10"> 
            <div class= "m-3">
              <h1>Lista de vacas</h1>
            </div>
            <button type="button" class="btn btn-primary btn-lg " data-toggle="modal" data-target="#exampleModalCenter">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg> 
              Actualizar lista
            </button>
             
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">
                            Actualizar lista de vacas
                          </h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <div class="form-group m-3">
                            <form action="/Vacas/actualizar_pago" action="{{'submit'}}" method="post">
                              @method('PUT')
                              @csrf
                                <label for="aldea">Aldea</label>
                                <select  name="id_aldea" class="form-control" id ="id_aldea">
                                    @foreach($aldeas as $aldea)
                                        <option value="{{$aldea->id}}">{{$aldea->nombre}}({{$aldea->coord_x }}/{{$aldea->coord_y }})</option>
                                    @endforeach
                                </select>
                                <label for="aldea">Vacas</label>
                                <input type="text" name="madera" class="form-control" id ="tropas"  >
                                <button type="submit" class="btn btn-outline-ligh bg-black m-3">Guardar información</button>
                            </form>
                          </div>
                      </div>
                    </div>
                </div>
            </div>
            <table id="taldeas" class="table table-bordered table-striped ">
                        <thead>
                                <tr>
                                    <th>Nombre aldea Lanza</th>
                                    <th>Nombre aldea vaca</th>
                                    <th>Nombre cuenta vaca</th>
                                    <th>Nombre alianza vaca</th>
                                    <th>Población</th>                        
                                </tr>
                            </thead>
                        <tbody>
                            @foreach($info as $aldea)
                            <tr>
                                    <td>{{$aldea->nombreLanza}} <a target="_blank" href="{{$aldea->ruta}}/position_details.php?x={{$aldea->aldeaLanzax}}&y={{$aldea->aldeaLanzay}}" >{{$aldea->aldeaLanzax }}{{ __('/') }}{{$aldea->aldeaLanzay }}</a></td>
                                    <td>{{$aldea->nombrealdeaVaca}} <a target="_blank" href="{{$aldea->ruta}}/position_details.php?x={{$aldea->vacax}}&y={{$aldea->vacay}}" >{{$aldea->vacax }}{{ __('/') }}{{$aldea->vacay }}</a> </td>
                                    <td><a target="_blank" href="{{$aldea->ruta}}/profile/{{$aldea->idcuentavaca}}" >{{$aldea->cuentaVaca}} </a></td>
                                    <td><a target="_blank" href="{{$aldea->ruta}}/alliance/{{$aldea->alivaca}}" >{{$aldea->alianzaVaca}}</a> </td>
                                    <td>{{$aldea->poblacion}}</td>
                            </tr>   
                            @endforeach
                        </tbody>
                        </foot>
                        </foot>
                    </table>
          </div>
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