@extends('adminlte::page')

@section('title', 'Mi cuenta')

@section('content_header')
    <h1 class="abs-center" > </h1>
@stop

@section('content')
   <div class="card">
    <div class="card-body">
      <div class="w-50 container">
        <div class="col margin">
          <div class="text-center mb-7"> 
          <div class= "m-3">
              <h1>Mi cuenta</h1>
            </div> 
        
                                  
          @foreach($info as $a)
            <form action="/MiCuenta/Modificar" action="{{'submit'}}" method="post">
              @method('PUT')
              @csrf
                <label for="nombreParam">Nombre de la cuenta</label>
                <input type="text" name="nombre" class="form-control" id ="nombre" value="{{$a->nombre_cuenta}}">
                <label for="nombreParam">Raza</label>
                <select  name="raza" class="form-control" id ="raza"> 
                  @foreach($raza as $m){
                      @if($a->raza==$m->valor)
                          <option value='{{$m->valor}}' selected = 'selected'>{{$m->nombre}}</option>
                      @else
                      <option value='{{$m->valor}}'>{{$m->nombre}}</option>
                      @endif    
                  @endforeach    
                </select>
                <label for="nombreParam">Servidor</label>
                <select  name="servidor" class="form-control" id ="servidor"> 
                  @foreach($servidor as $m){
                      @if($a->servidor==$m->id)
                          <option value='{{$m->id}}' selected = 'selected'>{{$m->nombre}}</option>
                      @else
                      <option value='{{$m->id}}'>{{$m->nombre}}</option>
                      @endif    
                  @endforeach    
                </select> 
                <label for="nombreParam">Nombre de la alianza</label>
                <input type="text" name="alianza" class="form-control" id ="nombre" value=" " disable>
                <label for="nombreParam">ID de telegram</label>
                <input type="text" name="telegram" class="form-control" id ="telegram" value="{{$a->id_telegram}}">              
                <div>
                  <h1>Artículo heroe</h1>  
                  <label for="aldeas_interes">Botas heroe</label>  
                  <select  name="heroe_botas" class="form-control" id ="heroe_botas">
                      <option value='0'>Sin botas</option>
                      <option value='0.25'>Botas del Mercenario (25%)</option>
                      <option value='0.5'>Botas del guerrero (50%)</option>
                      <option value='0.75'>Botas del Arconte (75%)</option>
                      
                  </select>
                  </div>
                 
                <button type="submit" class="btn btn-outline-ligh bg-black m-3">Guardar datos</button>
            </form>
          @endforeach    
                   
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