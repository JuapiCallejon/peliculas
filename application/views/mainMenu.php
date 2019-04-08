<script>
    $(document).ready( function () {
        $('#tabla').DataTable();

        $(document).on('click','.eliminarpelicula',function(e) {
            var r = confirm("Vas a eliminar un registro!\n¿Estás seguro?");

            if (r == false) {

                e.preventDefault();

            }else{

                var idPelicula = $(this).attr("value");
                $("." + idPelicula).remove();
                cadena = "<?php echo site_url('peliculas/eliminarPelicula'); ?>/" + idPelicula;
                $.ajax({
                    url: cadena
                });
            }
        });

        $(document).on('click','.modificarpelicula',function(e) {

            var id = $(this).attr("value");
            var nombre = $("." + id + " input[name='nombre']").val();
            var anyo = $("." + id + " input[name='anyo']").val();
            var sinopsis = $("." + id + " input[name='sinopsis']").val();
            var idDirector = $("#" + id + "-pelDir").val();
            var idGenero = $("#" + id + "-pelGen").val();


            var datosJson = {
                'id': id,
                'nombre': nombre,
                'anyo': anyo,
                'sinopsis': sinopsis,
                'idGenero': idGenero,
                'idDirector': idDirector
            };

            var cadena = "<?php echo site_url("peliculas/modificarPelicula/"); ?>";

            $.ajax({
                type: "POST",
                url: cadena,
                data: datosJson,
                error: function () { alert("error al modificar!!") }
            }).done(function () { alert("Modificación efectuada con exito!!"); });

        });

        $("#menuPeliculas").click(function(){
            location.href='<?php echo base_url("index.php/peliculas/homeFilms"); ?>'
        });

        $("#menuDirectores").click(function(){
            location.href='<?php echo base_url("index.php/directores/homeDirectors"); ?>'
        });

        $("#menuGeneros").click(function(){
            location.href='<?php echo base_url("index.php/generos/homeGenders"); ?>'
        });

        $("#menuUsuarios").click(function(){
            location.href='<?php echo base_url("index.php/usuarios/homeUsers"); ?>'
        });
            
            
    });
</script>
<?php
    echo "<div class='row container-fluid'>
        <div class='col-md-8 offset-4' id='navegacionMenu'>
            <button id='menuPeliculas' class='btn btn-info'>Peliculas</button>
            <button id='menuDirectores' class='btn btn-secondary botonesNavegacion'>Directores</button>
            <button id='menuGeneros' class='btn btn-secondary botonesNavegacion'>Generos</button>
            <button id='menuUsuarios' class='btn btn-secondary botonesNavegacion'>Usuarios</button>
        </div>
    </div>";
    echo "<div class='botonesPrincipales'>
        <button type='button' class='btn btn-success' data-toggle='modal' data-target='#insertModal'>
            Insertar una película
        </button>";
    echo "
        <button type='button' class='btn btn-danger' id='cerrarsesion'>";
    echo anchor("login/cerrar_session","Cerrar sesion");
    echo"</button>
        </div>
    <table id='tabla' class='display'>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Año</th>
            <th>Sinopsis</th>
            <th>Director</th>
            <th>Género</th>
            <th>Cartel</th>
            <th>Modificar</th>
            <th>Eliminar</th>
        </tr>
        </thead>";

        for ($i = 0; $i < count($pelList); $i++) {
            $pel = $pelList[$i];

            echo "
                <tr class='$pel->id'>
                    <input type='hidden' name='id' value='$pel->id'/>
                    <td><input type='text' name='nombre' value='$pel->nombre'/><p hidden>'$pel->nombre'</p></td>
                    <td><input type='text' name='anyo' value='$pel->anyo'/><p hidden>'$pel->anyo'</p></td>
                    <td><input type='text' name='sinopsis' value='$pel->sinopsis'/><p hidden>'$pel->sinopsis'</p></td>
                    <td><select id='$pel->id-pelDir'  multiple >";
                    
                        for ($j = $cont=0; $j < count($dirList); $j++) {
                            
                            $dir = $dirList[$j];
                            
                            for ($k = 0; $k < count($listaPeliculasDirectores); $k++) {
                                $pelDir = $listaPeliculasDirectores[$k];
                                
                                if(($pelDir->idDirector==$dir->id)&&($pelDir->idPelicula==$pel->id)){
                                    echo "<option  value='$dir->id' selected >$dir->nombre</option> "; 
                                    $k=count($listaPeliculasDirectores); 
                                }else if ($k==count($listaPeliculasDirectores)-1)  {
                                    echo "<option  value='$dir->id'   >$dir->nombre</option> ";
                                    $k=count($listaPeliculasDirectores);
                                }
                            }
                        }
                  
                   echo   "</select></td>
                    <td><select id='$pel->id-pelGen'  multiple >";

                        for ($j = $cont=0; $j < count($genList); $j++) {
                            
                            $gen = $genList[$j];
                        
                            for ($k = 0; $k < count($listaPeliculasGeneros); $k++) {
                                $pelGen = $listaPeliculasGeneros[$k];
                            
                                if(($pelGen->idGenero==$gen->id)&&($pelGen->idPelicula==$pel->id)){
                                    echo "<option  value='$gen->id' selected >$gen->nombre</option> "; 
                                    $k=count($listaPeliculasGeneros); 
                                }else if ($k==count($listaPeliculasGeneros)-1)  {
                                    echo "<option  value='$gen->id'   >$gen->nombre</option> ";
                                    $k=count($listaPeliculasGeneros);
                                }
                            }
                        }
                  
                   echo   "</select></td>
                    <td>"; echo "<img id='$pel->id-img' class='cartelPelicula' src='".base_url($pel->cartel)."' width='100px'>";echo "</td>
                    <td> <button  class='btn btn-primary modificarpelicula' value='$pel->id' >Modificar</button></td>
                    <td> <button class='btn btn-danger eliminarpelicula' value='$pel->id'>Eliminar</td>  
                </tr>
               
                ";
               

    }
        
        echo " </table>
        </div>";

        include("modalInsertPelicula.php");
    ?>
    