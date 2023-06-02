@extends('adminlte::page')

@section('title', 'Mis rutas de comercio')

@section('content_header')
    <h1 class="abs-center" > </h1>
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

<script type="text/javascript">
    var prodAldeas = @json($ProdAldeas);
    let totalMadera = 0;
    let totalBarro = 0;
    let totalHierro = 0;
    let totalCereal = 0;
    let totalConsumo = 0;
    let totalpc = 0;
    let totalprodu = 0;
    let seleccion = 0;
</script>

 
 
<script>
  $(function () {
        $('#tconstrucciones').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": false,
      "autoWidth": false,
      "responsive": true,
    });

   
  });
</script>
<script type="text/javascript">
     
    function cambiarEleccion(cambio){

     document.getElementById("tproduccion").rows[1].cells[1].innerHTML= 0;
        document.getElementById("tproduccion").rows[1].cells[2].innerHTML= 0;
        document.getElementById("tproduccion").rows[1].cells[3].innerHTML= 0;
        document.getElementById("tproduccion").rows[1].cells[4].innerHTML =0;
        document.getElementById("tproduccion").rows[1].cells[5].innerHTML= 0;
        var etiqueta = "";

        if (cambio==-1){ 
            
             
                for (i = 0; i < prodAldeas.length; i++) {
                    etiqueta = prodAldeas[i]["coord_x"]+""+prodAldeas[i]["coord_y"];
                    document.getElementById(etiqueta).checked=false;
                } 

            if(document.getElementById("todasAldeas").checked){ 
                //poner todas a 1aldeas a 1  y sumar los valores
                for (i = 0; i < prodAldeas.length; i++) {
                        document.getElementById("tproduccion").rows[1].cells[1].innerHTML = (parseInt(document.getElementById("tproduccion").rows[1].cells[1].innerHTML)+parseInt(prodAldeas[i]["madera"]));
                        document.getElementById("tproduccion").rows[1].cells[2].innerHTML = (parseInt(document.getElementById("tproduccion").rows[1].cells[2].innerHTML)+parseInt(prodAldeas[i]["barro"]));
                        document.getElementById("tproduccion").rows[1].cells[3].innerHTML = (parseInt(document.getElementById("tproduccion").rows[1].cells[3].innerHTML)+parseInt(prodAldeas[i]["hierro"]));
                        document.getElementById("tproduccion").rows[1].cells[4].innerHTML = (parseInt(document.getElementById("tproduccion").rows[1].cells[4].innerHTML)+parseInt(prodAldeas[i]["cereal"]));
                        document.getElementById("tproduccion").rows[1].cells[5].innerHTML = (parseInt(document.getElementById("tproduccion").rows[1].cells[5].innerHTML)+parseInt(prodAldeas[i]["total"]));
                } 
             
            } 
                
             
            
        }else{
            document.getElementById("todasAldeas").checked= false;
          
            for (i = 0; i < prodAldeas.length; i++) {
                if(document.getElementById(prodAldeas[i]["coord_x"]+""+prodAldeas[i]["coord_y"]).checked){
                    document.getElementById("tproduccion").rows[1].cells[1].innerHTML = (parseInt(document.getElementById("tproduccion").rows[1].cells[1].innerHTML)+parseInt(prodAldeas[i]["madera"]));
                    document.getElementById("tproduccion").rows[1].cells[2].innerHTML = (parseInt(document.getElementById("tproduccion").rows[1].cells[2].innerHTML)+parseInt(prodAldeas[i]["barro"]));
                    document.getElementById("tproduccion").rows[1].cells[3].innerHTML = (parseInt(document.getElementById("tproduccion").rows[1].cells[3].innerHTML)+parseInt(prodAldeas[i]["hierro"]));
                    document.getElementById("tproduccion").rows[1].cells[4].innerHTML = (parseInt(document.getElementById("tproduccion").rows[1].cells[4].innerHTML)+parseInt(prodAldeas[i]["cereal"]));
                    document.getElementById("tproduccion").rows[1].cells[5].innerHTML = (parseInt(document.getElementById("tproduccion").rows[1].cells[5].innerHTML)+parseInt(prodAldeas[i]["total"]));
                }
            }

        }
        calcularTotales();
    }
    

    function calcularTotales(){
        let numeroFilas =  $("#topciones tr").length;
        let TotalMats = totalMadera+totalBarro+totalHierro+totalCereal;
        document.getElementById("topciones").rows[1].cells[3].innerHTML = totalMadera;
        document.getElementById("topciones").rows[1].cells[4].innerHTML = totalBarro;
        document.getElementById("topciones").rows[1].cells[5].innerHTML = totalHierro;
        document.getElementById("topciones").rows[1].cells[6].innerHTML = totalCereal;
        document.getElementById("topciones").rows[1].cells[7].innerHTML = TotalMats;
        document.getElementById("topciones").rows[1].cells[8].innerHTML = TotalMats;
        document.getElementById("topciones").rows[1].cells[9].innerHTML = totalConsumo;
        document.getElementById("topciones").rows[1].cells[10].innerHTML = totalpc;
        
         //Calculo horario npc y sin npc
        var prodMadera = document.getElementById("tproduccion").rows[1].cells[1].innerHTML;
        var prodBarro = document.getElementById("tproduccion").rows[1].cells[2].innerHTML;
        var prodHierro = document.getElementById("tproduccion").rows[1].cells[3].innerHTML;
        var prodCereal = document.getElementById("tproduccion").rows[1].cells[4].innerHTML;
        var prodTotal= document.getElementById("tproduccion").rows[1].cells[5].innerHTML;
        
        
        var decimal = TotalMats/prodTotal;    
        
        let horas = Math.floor(decimal); // Obtenemos la parte entera
        let restoHoras = Math.floor(decimal % 1 * 100); // Obtenemos la parde decimal
        let decimalMinutos = restoHoras * 60 / 100; // Obtenemos los minutos expresado en decimal

        let minutos = Math.floor(decimalMinutos); // Obtenemos la parte entera
        let restoMins = Math.floor(decimalMinutos % 1 * 100); // Obtenemos la parde decimal
        let segundos = Math.floor(restoMins * 60 / 100); // Obtenemos los segundos expresado en entero*/
            
        document.getElementById("tproduccion").rows[1].cells[6].innerHTML=  horas+"h:"+minutos+"mm:"+segundos +"s";
        //Parte de nateruas totales en aldea
         
       
           
        var mad = document.getElementById("maldea").value;
        var bar = document.getElementById("baldea").value;
        var hi = document.getElementById("haldea").value;
        var cere = document.getElementById("caldea").value;

        if (!isNaN(mad)&&!isNaN(bar)) {

        }

      
         var total = parseInt(mad)+parseInt(bar)+parseInt(hi)+parseInt(cere);
            document.getElementById("tproduccion").rows[2].cells[5].innerHTML=  total;

         decimal = (TotalMats-total)/prodTotal;    
        
         horas = Math.floor(decimal); // Obtenemos la parte entera
         restoHoras = Math.floor(decimal % 1 * 100); // Obtenemos la parde decimal
         decimalMinutos = restoHoras * 60 / 100; // Obtenemos los minutos expresado en decimal

         minutos = Math.floor(decimalMinutos); // Obtenemos la parte entera
         restoMins = Math.floor(decimalMinutos % 1 * 100); // Obtenemos la parde decimal
         segundos = Math.floor(restoMins * 60 / 100); // Obtenemos los segundos expresado en entero*/
        if(total<=TotalMats){
            document.getElementById("tproduccion").rows[1].cells[7].innerHTML=  horas+"h:"+minutos+"mm:"+segundos +"s";
        }else{
            document.getElementById("tproduccion").rows[1].cells[7].innerHTML=  "Ya puedes hacerlas construcciones";
        }
        
    }
    $(document).on('click', '.borrar', function(event) {
        event.preventDefault();
        $(this).closest('tr').remove();
        calcularTotales(); 
    });
        
</script>
<script>
  
        
        function insertarFila2(nom,lvl,m,b,h,c,con,p,prod,ElementoCant){
            
            var input = document.getElementById(ElementoCant);
            var Cantidad = input.value;
            //console.log(Cantidad);

            if (!isNaN(Cantidad)) {
                var tabla = document.getElementById("topciones");

                let tr = document.createElement("tr");
                
                let th = document.createElement("th");
                let thText = document.createTextNode(nom);
                th.appendChild(thText);
                tr.appendChild(th);

                th = document.createElement("th");
                thText = document.createTextNode(lvl);
                th.appendChild(thText);
                tr.appendChild(th);

                th = document.createElement("th");
                thText = document.createTextNode(Cantidad);
                th.appendChild(thText);
                tr.appendChild(th);

                th = document.createElement("th");
                thText = document.createTextNode(m);
                th.appendChild(thText);
                tr.appendChild(th);

                th = document.createElement("th");
                thText = document.createTextNode(b);
                th.appendChild(thText);
                tr.appendChild(th);

                th = document.createElement("th");
                thText = document.createTextNode(h);
                th.appendChild(thText);
                tr.appendChild(th);

                th = document.createElement("th");
                thText = document.createTextNode(c);
                th.appendChild(thText);
                tr.appendChild(th);

                var total = m+b+h+c;
                th = document.createElement("th");
                thText = document.createTextNode(total);
                th.appendChild(thText);
                tr.appendChild(th);
                var total = (m+b+h+c)*Cantidad;
                th = document.createElement("th");
                thText = document.createTextNode(total);
                th.appendChild(thText);
                tr.appendChild(th);

                th = document.createElement("th");
                thText = document.createTextNode(con);
                th.appendChild(thText);
                tr.appendChild(th);

                th = document.createElement("th");
                thText = document.createTextNode(p);
                th.appendChild(thText);
                tr.appendChild(th);


                th = document.createElement("th");
                var campo4 = document.createElement("input");
                campo4.type = "button";
                campo4.value = "Borrar Fila";
                campo4.setAttribute("class", "borrar btn"); 
                campo4.onclick= function() {
                    totalCereal = totalCereal -(c*Cantidad);
                    totalMadera = totalMadera-(m*Cantidad);
                    totalBarro = totalBarro-(b*Cantidad);
                    totalHierro = totalHierro-( h*Cantidad);
                    totalConsumo = totalConsumo-(con*Cantidad);
                    totalpc = totalpc-(p*Cantidad);

                }
                th.appendChild(campo4);
                tr.appendChild(th);

                tabla.appendChild(tr);

                totalCereal = totalCereal +(c*Cantidad);
                totalMadera = totalMadera+(m*Cantidad);
                totalBarro = totalBarro+(b*Cantidad);
                totalHierro = totalHierro+( h*Cantidad);
                totalConsumo = totalConsumo+(con*Cantidad);
                totalpc = totalpc+(p*Cantidad);
                calcularTotales(); 
            }else{
                window.alert("Por favor, en la columna cantidad solo se pueden introducir valores numéricos")
            }
            
        }
        
</script>
@stop
@section('content')
   <div class="card">
    <div class="card-body ">
      <div class=" w-100">
        <div class="col margin   ">
          <div class="text-center mb-10 "> 
            <div class= "m-3">
              <h1>NPC</h1>
            </div>
            <a href="/Calculos/npc" class="btn btn-primary btn-lg "   btn-block btn-sm"> Refrescar</a>
            <div class="col table-responsive w-auto">
              <table id="topciones" class="table table-bordered table-striped ">
               <thead>
                <tr>
                    <th>Nombre edificio</th>
                    <th>Nivel</th>
                    <th>Cantidad</th>
                    <th>Madera</th>
                    <th>Barro</th>
                    <th>Hierro</th>
                    <th>Cereal</th>
                    <th>Total Unitaria</th>
                    <th>Total Materias</th>
                    <th>Consumo</th>
                    <th>PC</th>
                    <th>Opciones</th>
                </tr>
                </thead>
                <tbody>
                  <th></th>
                  <th>Total:</th>
                  <th>0</th>
                  <th>0</th>
                  <th>0</th>
                  <th>0</th>
                  <th>0</th>
                  <th>0</th>
                  <th>0</th>
                  <th>0</th>
                  <th>0</th>
                  <th></th>
                </tbody>
                <tfoot>
                </tfoot>
              </table>
              <div class= "m-3">
              <h1>Producción total</h1>
            </div>
              <div class="form-check form-check-primary form-check-inline" onChange="cambiarEleccion(-1)">
                        <input class="form-check-input" type="checkbox" id="todasAldeas" >
                        <label class="form-check-label" for="form-check-default-checked">
                            Todas las aldeas
                        </label>
              </div>
              @php
                  $contador1 = 0;
                  

              @endphp

              @foreach($ProdAldeas as $aldea)
                <div class="form-check form-check-primary form-check-inline"onChange="cambiarEleccion({{$contador1}})">
                  <input class="form-check-input" type="checkbox"  id="{{$aldea['coord_x']}}{{$aldea['coord_y']}}" >
                  <label class="form-check-label" for="form-check-default-checked" >
                    {{$aldea['nombre']}}
                  </label>
                </div>
                  @php
                    $contador1 = $contador1 + 1;
                  @endphp
              @endforeach
              <div class= "m-3">
              <h1>Construcciones</h1>
            </div>
              <table id="tproduccion" class="table table-bordered table-striped  table-responsive">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Madera</th>
                            <th>Barro</th>
                            <th>Hierro</th>
                            <th>Cereal</th>
                            <th>Producción total MATS</th>
                            <th>Tiempo con NPC</th>
                            <th>Tiempo con NPC con materias de las aldeas</th>
                            <th>Tiempo sin NPC</th>
                            <th>Tiempo sin NPC con materias de las aldeas</th>
                        </tr>
                        <tr>
                            <th id ="asd"  >Producción para el cálculo</th>
                            <th id ="ProducionMadera" value="0" >0</th>
                            <th id ="ProducionBarro" >0</th>
                            <th id ="ProducionHierro" >0</th>
                            <th id ="ProducionCereal" >0</th>
                            <th id ="ProducionTotal" >0</th>
                            <th>00h:00mm:00s</th>
                            <th>00h:00mm:00s</th>
                            <th>00h:00mm:00s</th>
                            <th>00h:00mm:00s</th>
                        </tr>
                        <tr>
                             <th>Materias aldeas</th>
                            <th id ="madera" value = "0" ><input onchange= "calcularTotales()" type="text" value ="0" name="b" class="form-control" id ="maldea"  pattern="^[0-9]+"></th>
                            <th id ="barro" value = "0" ><input onchange= "calcularTotales()" type="text" value ="0" name="b" class="form-control" id ="baldea"  pattern="^[0-9]+"></th>
                            <th id ="hierro"  value = "0"><input onchange= "calcularTotales()" type="text" value ="0" name="b" class="form-control" id ="haldea"  pattern="^[0-9]+"> </th>
                            <th id ="cereal"  value = "0"><input onchange= "calcularTotales()" type="text" value ="0" name="b" class="form-control" id ="caldea"  pattern="^[0-9]+"> </th>
                            <th id ="total"  value = "0">0</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                           
                        </tbody>
     
                    </table>
                    <table id="tconstrucciones" class="table table-bordered table-striped ">
                        <thead>
                        <tr>
                            <th>Nombre edificio</th>
                            <th>Nivel</th>
                            <th>Cantidad</th>
                            <th>Madera</th>
                            <th>Barro</th>
                            <th>Hierro</th>
                            <th>Cereal</th>
                            <th>Total Materias</th>
                            <th>Consumo</th>
                            <th>PC</th>
                            <th>Opciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                        $contador = 0;
                        @endphp

                        @foreach($construcciones as $registro)
                        @php
                            $nombre = $registro->nombre_ed;
                            $nivel = $registro->nivel;
                            $ma = $registro->madera;
                            $ba = $registro->barro;
                            $hi = $registro->hierro;
                            $cere = $registro->cereal;
                            $consu = $registro->consumo;
                            $p = $registro->pc;
                            $prod = $registro->produccion;
                            $totalMAterias = $ma+$ba+$hi+$cere;
                            
                         @endphp
                        <tr>
                        <td >{{$registro->nombre_ed}}</td>
                        <td>{{$registro->nivel}}</td>
                        <td ><input type="text" value ="1" name="Nvagones" class="form-control" id ="{{$contador}}"  pattern="^[0-9]+"></td>
                        <td>{{$registro->madera}}</td>
                        <td>{{$registro->barro}}</td>
                        <td>{{$registro->hierro}}</td>
                        <td>{{$registro->cereal}}</td>
                        <td>{{$totalMAterias}}</td>
                        <td>{{$registro->consumo}}</td>
                        <td>{{$registro->pc}}</td>
                        <td >
                        <Button type ="button" onclick="insertarFila2('{{$nombre}}',{{$nivel}},{{$ma}},{{$ba}},{{$hi}},{{$cere}},{{$consu}},{{$p}},{{$prod}},{{$contador}})" class="btn btn-success">Insertar fila</Button>
                        </td>
                        @php
                        $contador = $contador + 1;;
                        @endphp
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>       
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
