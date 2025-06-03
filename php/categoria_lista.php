<?php 
    $inicio = ($pagina>0) ? (($registros * $pagina) - $registros) : 0; #calculo de la pagina inicial
    $tabla = "";

    if(isset($busqueda) && $busqueda != ""){ # si la variable busqueda no esta vacia  Genera la consulta que treaera los categorias y el total de categorias
        $consulta_datos = "SELECT * FROM categoria 
                        WHERE 
                            categoria_nombre LIKE '%$busqueda%' 
                            OR categoria_ubicacion LIKE '%$busqueda%'
                        ORDER BY categoria_nombre ASC LIMIT $inicio,$registros;";

        $consulta_total = "SELECT COUNT(categoria_id) FROM categoria 
                        WHERE 
                            categoria_nombre LIKE '%$busqueda%' 
                            OR categoria_ubicacion LIKE '%$busqueda%';";

    }else{
        $consulta_datos = "SELECT * FROM categoria 
                        ORDER BY categoria_nombre ASC LIMIT $inicio,$registros;";

        $consulta_total = "SELECT COUNT(categoria_id) FROM categoria;";
    }

    $conexion = conexion();

    $datos = $conexion->query($consulta_datos); #ejecutamos la consulta de los datos
    $datos = $datos->fetchAll(); #fetchAll() devuelve todos los registros de la consulta en un array asociativo
    $total = $conexion->query($consulta_total); #ejecutamos la consulta del total de registros
    $total = (int) $total->fetchColumn(); #fetchColumn() devuelve el valor de la primera columna de la primera fila del resultado de la consulta

    $Npaginas = ceil($total / $registros); #ceil redondea hacia arriba 

    # abrimos la tabla 
    $tabla .= ' 
        <div class="table-container">
            <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                <thead>
                    <tr class="has-text-centered">
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Ubicación</th>
                    <th>Productos</th>
                    <th colspan="2">Opciones</th>
                    </tr>
                </thead>
                <tbody>
    ';

    if($total >= 1 && $pagina <= $Npaginas){ #si hay registros y la pagina es menor o igual a la cantidad de paginas agregamos los registros a la tabla ademas de los numeros de la paginacion
        $contador = $inicio + 1; #contador para mostrar el numero de registro mas uno ya que el inicia en 0
        $pag_inicio = $inicio + 1; 
        foreach($datos as $rows){
            $tabla .= '
                <tr class="has-text-centered" >
					<td>'.$contador.'</td>
                    <td>'.substr($rows["categoria_nombre"], 0, 25).'</td>
                    <td>'.$rows["categoria_ubicacion"].'</td>
                    <td>
                        <a href="index.php?vista=product_category&category_id='.$rows["categoria_id"].'" class="button is-link is-rounded is-small">Ver productos</a>
                    </td>
                    <td>
                        <a href="index.php?vista=category_update&category_id_up='.$rows["categoria_id"].'" class="button is-success is-rounded is-small">Actualizar</a>
                    </td>
                    <td>
                        <a href="'.$url.$pagina.'&category_id_del='.$rows["categoria_id"].'" class="button is-danger is-rounded is-small">Eliminar</a>
                    </td>
                </tr>
            ';
            $contador++;

        }
        $pag_final = $contador - 1;
    }else{
        if($total >= 1){ #si hay registros pero la pagina es mayor a la cantidad de paginas mostramos un mensaje de error
            $tabla .= '
                <tr class="has-text-centered" >
                    <td colspan="6">
                        <a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
                            Haga clic acá para recargar el listado
                        </a>
                    </td>
                </tr>
            ';
        }else{
            $tabla .= '
                <tr class="has-text-centered" >
                    <td colspan="6">
                        No hay registros en el sistema
                    </td>
                </tr>
            ';
        }
    }

    $tabla .= '
                </tbody>
            </table>
        </div>
    ';
    
    #si hay registros y la pagina es menor o igual a la cantidad de paginas mostramos el total de registros y los numeros de la paginacion
    if($total >= 1 && $pagina <= $Npaginas){
        $tabla .= '<p class="has-text-right">Mostrando categorias <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
    }

    $conexion = null; #cerramos la conexion a la base de datos
    echo $tabla;

    #si hay registros y la pagina es menor o igual a la cantidad de paginas mostramos los numeros de la paginacion
    if($total >= 1 && $pagina <= $Npaginas){
        echo paginador_tablas($pagina, $Npaginas, $url, 3);
    }