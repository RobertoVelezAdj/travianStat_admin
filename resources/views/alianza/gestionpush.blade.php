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
              <h1>Push pendientes</h1>
              <div class="margin">
                <div class="btn-group  ">
                  <button type="button" class="btn   btn-info btn-info">Acciones</button>
                  <button type="button " class="btn   dropdown-toggle dropdown-icon btn-info" data-toggle="dropdown">
                  <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu" role="menu">
                   <button class="dropdown-item btn-info" data-toggle="modal" data-target="#Push">Push de alianza</button>

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
                          <form action="/generarPush" action="{{'submit'}}" method="post">
                              @method('PUT')
                              @csrf
                              <label for="nombreParam">Coordenada x</label>
                              <input type="number" name="coor_x" class="form-control" id ="coor_x"   value = "0" pattern="[-]*[0-9]+"required>
                              <label for="nombreParam">Coordenada y</label>
                              <input type="number" name="coor_y" class="form-control" id ="coor_y" value = "0" pattern="[-]*[0-9]+" required>
                              <label for="nombreParam">Cantidad Materias</label>
                              <input type="number" name="cantidad" class="form-control" id ="coor_y" value = "0" required>
                              <button type="submit" class="btn btn-danger">Realizar push</button>
                          </form>
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
                  <th>Nombre cuenta</th>
                  <th>Estado</th>
                  <th>Cantidad</th>
                </tr>
              </thead>
              <tbody>
                @foreach($push as $p)
                  <tr>
                    <th>{{$p->nombre_cuenta}} </th>
                    <th> 
                      
                        <?php
                        if ($p->estado<1) {
                            echo "Pendiente envío";
 
                        } else {
                            echo "Enviado";
                        }
                        ?>

                    </th>
                    <th>{{$p->cantidad}}</th>
                    
                  </tr>
                    
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