@extends('adminlte::page')

@section('title', 'Apuestas')

@section('content_header')
    <h1 class="abs-center" > </h1>
@stop

@section('content')
            <!-- /.card-header -->
        <div class="card ">
            <div class="container w-auto">
              <div class="col margin">
                <div class="text-center mb-7"> 
                  <div class= "m-3">
                    <canvas id="speedChart" width="600" height="400"></canvas>
                    <h1>Historico por día</h1>
                  </div>
              </div>
              <div class="col  table-responsive">
                 
                   <table id="example1" class="table table-bordered table-striped ">
                    <thead>
                      <tr>
                          <th>Día</th>
                          <th>Contador apuestas</th>
                          <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach($apuestas as $a)
                          @php 
                              if(round($a->resultadodinero,2)< 0){
                                echo "<tr class='table-danger'>";
                              }elseif(round($a->resultadodinero,2) >0){
                                echo " <tr class='table-success'>";
                              }else{
                                echo " <tr>";
                              }
                            @endphp
                            <th>{{$a->fecha2}}</th>
                            <th>{{$a->contador}}</th>
                            <th>@php echo round($a->resultadodinero,2); @endphp€</th>
                        </tr>   
                        @endforeach
                      </tbody>
                  </table>
               </div>

            </div>
          </div>
              <!-- /.card-body -->
         
            <div class="container w-auto">
              <div class="col margin">
                <div class="text-center mb-7"> 
                  <div class= "m-3">
                    <h1>Historico por deportes</h1>
                  </div>
              </div>
              <div class="col  table-responsive">
                 
                   <table id="example2" class="table table-bordered table-striped ">
                    <thead>
                      <tr>
                          <th>Deporte</th>
                          <th>Contador apuestas</th>
                          <th>Total</th>
                          
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($tabla_deportes as $a)
                          @php 
                            if(round($a->resultadodinero,2)< 0){
                              echo "<tr class='table-danger'>";
                            }elseif(round($a->resultadodinero,2) >0){
                              echo " <tr class='table-success'>";
                            }else{
                              echo " <tr>";
                            }
                          @endphp
                          
                              <th>{{$a->deporte}}</th>
                              <th>{{$a->contador}}</th>
                              <th>@php echo round($a->resultadodinero,2); @endphp€</th>
                          </tr>   
                        @endforeach
                      </tbody>
                  </table>
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
  
</script>
<script>
 $(function () {
    $("#example2").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
    
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
<script  src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
 
<script>
  

var speedCanvas = document.getElementById("speedChart");

Chart.defaults.global.defaultFontFamily = "Lato";
Chart.defaults.global.defaultFontSize = 18;

@php
$label ="labels: [";
$dataset ="data: [";
$datasetApostado ="data: [";
@endphp
@foreach($historico as $a)
@php 
    $label = $label.'"'.$a->created_at.'",';
    $dataset = $dataset.'"'.$a->dineroStack.'",';
    $datasetApostado = $datasetApostado.'"'.$a->dineroEnApuestas.'",';
  @endphp 
@endforeach


var dataFirst = {
    label: "Dinero STACK",
    @php 
  $dataset = $dataset.'],';
  echo $dataset;
  @endphp 
    lineTension: 0,
    fill: false,
    borderColor: 'red'
  };

var dataSecond = {
    label: "Dinero apostado",
    
    @php 
  $datasetApostado = $datasetApostado.'],';
  echo $datasetApostado;
  @endphp 
    lineTension: 0,
    fill: false,
  borderColor: 'blue'
  };
 

var speedData = {
  @php 
  $label = $label.'],';
  echo $label;
  @endphp 
  datasets: [dataFirst, dataSecond]
};
 

var chartOptions = {
  legend: {
    display: true,
    position: 'top',
    labels: {
      boxWidth: 80,
      fontColor: 'black'
    }
  }
};

var lineChart = new Chart(speedCanvas, {
  type: 'line',
  data: speedData,
  options: chartOptions
});


</script>

 @stop