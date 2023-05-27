@extends('adminlte::page')

@section('title', 'Apuestas')

@section('content_header')
    <h1 class="abs-center" > </h1>
@stop

@section('content')
   <div class="card">
    <div class="card-body">
      <div class="container">
        <div class="col margin">
          <div class="text-center mb-7"> 
            
        
        <div class="col table-responsive">
              <table id="example1" class="table table-bordered table-striped ">
              <thead class="table-dark">
                <tr>
                  <th>Nombre aldea</th>
                  <th>Cuartel</th>
                  <th>Cuartel grande</th>
                  <th>Establo</th>
                  <th>Establo grande</th>
                  <th>Taller</th>
                  <th>Ayuntamiento</th>
                  <th>Plaza de torneos</th>
                  <th>Oficina de comercio</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
               
                @foreach($aldeas as $aldea)
                  <tr>
                    <th>{{$aldea->nombre}} ({{$aldea->coord_x}}/{{$aldea->coord_y}})</th>
                    <th>{{$aldea->cuartel}}</th>
                    <th>{{$aldea->cuartel_g}}</th>
                    <th>{{$aldea->establo}}</th>
                    <th>{{$aldea->establo_g}}</th>
                    <th>{{$aldea->taller}}</th>
                    <th>{{$aldea->ayuntamiento}}</th>
                    <th>{{$aldea->p_torneos}}</th>
                    <th>{{$aldea->o_comercio}} </th>
                    <th> 
                      <div class="margin">
                        <div class="btn-group  ">
                          <button type="button" class="btn   btn-info btn-info">Acciones</button>
                          <button type="button " class="btn   dropdown-toggle dropdown-icon btn-info" data-toggle="dropdown">
                          <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                          <button class="dropdown-item btn-info" data-toggle="modal" data-target="#editarAldea-{{$aldea->id_aldea}}">Editar aldea</button>
 
                        </div>
                      </div>
                    </th>
                  </tr>
                  <div class="modal fade" id="editarAldea-{{$aldea->id_aldea}}" tabindex="-1" role="dialog" aria-labelledby="editarAldea-{{$aldea->id_aldea}}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Editar edificios de la aldea "{{$aldea->nombre}}{{ __('  ') }} ({{$aldea->coord_x }}{{ __('/') }}{{$aldea->coord_y }})" </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <form action="/Aldeas/Editaredificios" method="POST">
                                    @method('PUT')
                                    @csrf
                                       <label for="madera">Cuartel:</label>
                                      <input type="number" min ="0" max ="20" name="cuartel" class="form-control" id ="cuartel"  value = "{{$aldea->cuartel}}" pattern="^[0-9]+">
                                        
                                      <label for="madera">Cuartel grande:</label>
                                      <input type="number"min ="0" name="cuartel_g" class="form-control" id ="cuartel_g"  value = "{{$aldea->cuartel_g}}" pattern="^[0-9]+">
                                      
                                      <label for="madera">Establo:</label>
                                      <input type="number" min ="0" name="establo" class="form-control" id ="establo"  value = "{{$aldea->establo}}" pattern="^[0-9]+">
                                        
                                      <label for="madera">Establo grande:</label>
                                      <input type="number" min ="0" name="establo_g" class="form-control" id ="establo_g"  value = "{{$aldea->establo_g}}" pattern="^[0-9]+">

                                      <label for="madera">Taller:</label>
                                      <input type="number" min ="0" name="taller" class="form-control" id ="taller"  value = "{{$aldea->taller}}" pattern="^[0-9]+">
                                           
                                      <label for="madera">Ayuntamiento:</label>
                                      <input type="number" min ="0" name="ayuntamiento" class="form-control" id ="ayuntamiento"  value = "{{$aldea->ayuntamiento}}" pattern="^[0-9]+">
                                           
                                      <label for="madera">Plaza de torneos:</label>
                                      <input type="number" min ="0" name="p_torneos" class="form-control" id ="p_torneos"  value = "{{$aldea->p_torneos}}" pattern="^[0-9]+">
                                           
                                      <label for="madera">Oficina de comercio:</label>
                                      <input type="number" name="o_comercio" class="form-control" id ="o_comercio"  value = "{{$aldea->o_comercio}}" pattern="^[0-9]+">
                                      <input  name="id_aldea" type="hidden" value="{{$aldea->id_aldea}}">    
                                      <button type="button"class="btn btn-primary" data-dismiss="modal" aria-label="Close">Cancelar</button>
                                        <button type="submit" class="btn btn-info">Editar</button>
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