<?php 
    $inicio = ($pagina>0) ? (($registros * $pagina) - $registros) : 0; #calculo de la pagina inicial
    $tabla = "";

    if(isset($busqueda) && $busqueda != ""){ # si la variable busqueda no esta vacia  Genera la consulta que treaera los usuarios y el total de usuarios
        $consulta_datos = "SELECT * FROM usuario 
                        WHERE usuario_id != '".$_SESSION['id']."' 
                        AND (
                            usuario_nombre LIKE '%$busqueda%' 
                            OR usuario_apellido LIKE '%$busqueda%' 
                            OR usuario_usuario LIKE '%$busqueda%' 
                            OR usuario_email LIKE '%$busqueda%') 
                        ORDER BY usuario_nombre ASC LIMIT $inicio,$registros";

        $consulta_total = "SELECT COUNT(usuario_id) FROM usuario 
                        WHERE usuario_id != '".$_SESSION['id']."' 
                        AND (
                            usuario_nombre LIKE '%$busqueda%' 
                            OR usuario_apellido LIKE '%$busqueda%' 
                            OR usuario_usuario LIKE '%$busqueda%' 
                            OR usuario_email LIKE '%$busqueda%')";

    }else{
        $consulta_datos = "SELECT * FROM usuario 
                        WHERE usuario_id != '".$_SESSION['id']."' 
                        ORDER BY usuario_nombre ASC LIMIT $inicio,$registros";

        $consulta_total = "SELECT COUNT(usuario_id) FROM usuario 
                        WHERE usuario_id != '".$_SESSION['id']."'";
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
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th colspan="2">Opciones</th>
                    </tr>
                </thead>
                <tbody>
    ';

    if($total >= 1 && $pagina <= $Npaginas){ #si hay registros y la pagina es menor o igual a la cantidad de paginas agregamos los registros a la tabla adeemas de los numeros de la paginacion
        $contador = $inicio + 1; #contador para mostrar el numero de registro mas uno ya que el inicia en 0
        $pag_inicio = $inicio + 1; 
        foreach($datos as $rows){
            $tabla .= '
                <tr class="has-text-centered" >
					<td>'.$contador.'</td>
                    <td>'.$rows["usuario_nombre"].'</td>
                    <td>'.$rows["usuario_apellido"].'</td>
                    <td>'.$rows["usuario_usuario"].'</td>
                    <td>'.$rows["usuario_email"].'</td>
                    <td>
                        <a href="index.php?vista=user_update&user_id_up='.$rows["usuario_id"].'" class="button is-success is-rounded is-small">Actualizar</a>
                    </td>
                    <td>
                        <a href="'.$url.$pagina.'&user_id_del='.$rows["usuario_id"].'" class="button is-danger is-rounded is-small">Eliminar</a>
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
                    <td colspan="7">
                        <a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
                            Haga clic acá para recargar el listado
                        </a>
                    </td>
                </tr>
            ';
        }else{
            $tabla .= '
                <tr class="has-text-centered" >
                    <td colspan="7">
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
        $tabla .= '<p class="has-text-right">Mostrando usuarios <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
    }

    $conexion = null; #cerramos la conexion a la base de datos
    echo $tabla;

    #si hay registros y la pagina es menor o igual a la cantidad de paginas mostramos los numeros de la paginacion
    if($total >= 1 && $pagina <= $Npaginas){
        echo paginador_tablas($pagina, $Npaginas, $url, 3);
    }