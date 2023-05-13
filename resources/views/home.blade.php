@extends('adminlte::page')

@section('title', 'travianSTAT.es')

@section('content_header')
    <h1 class="abs-center" >Apuestas en vivo</h1>
@stop

@section('content')
            <!-- /.card-header -->
        <div class="card">
            <div class="card-body">
                 <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                  <th>Rendering engine</th>
                    <th>Browser</th>
                    <th>Platform(s)</th>
                    <th>Engine version</th>
                    <th>CSS grade</th>
                    <th>Rendering engine</th>
                    <th>Browser</th>
                    <th>Platform(s)</th>
                    <th>Engine version</th>
                    <th>CSS grade</th>
                    <th>Rendering engine</th>
                    <th>Rendering engine</th>
                    <th>Browser</th>
                    <th>Platform(s)</th>
                    <th>Engine version</th>
                    <th>CSS grade</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td>Browser</td>
                    <td>Browser</td>
                    <td>Platform(s)</td>
                    <td>Engine version</td>
                    <td>CSS grade</td>
                    <td>Rendering engine</td>
                    <td>Browser</td>
                    <td>Platform(s)</td>
                    <td>Engine version</td>
                    <td>CSS grade</td>
                    <td>Rendering engine</td>
                    <td>Rendering engine</td>
                    <td>Browser</td>
                    <td>Platform(s)</td>
                    <td>Engine version</td>
                    <td>CSS grade</td>
                  </tr>

                  </tbody>
                  <tfoot>
                  <tr>
                    <td>Browser</td>
                    <td>Browser</td>
                    <td>Platform(s)</td>
                    <td>Engine version</td>
                    <td>CSS grade</td>
                    <td>Rendering engine</td>
                    <td>Browser</td>
                    <td>Platform(s)</td>
                    <td>Engine version</td>
                    <td>CSS grade</td>
                    <td>Rendering engine</td>
                    <td>Rendering engine</td>
                    <td>Browser</td>
                    <td>Platform(s)</td>
                    <td>Engine version</td>
                    <td>CSS grade</td>
                  </tr>
                  </tfoot>
                </table>
               
              <!-- /.card-body -->
            </div>
        </div>
 @stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
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
@stop