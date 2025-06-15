<?php 
    $inicio = ($pagina>0) ? (($registros * $pagina) - $registros) : 0; #calculo de la pagina inicial
    $tabla = "";

    $campos = "producto.producto_id, producto.producto_codigo, producto.producto_nombre, producto.producto_precio, producto.producto_stock, producto.producto_foto, producto.categoria_id, producto.usuario_id, categoria.categoria_id, categoria.categoria_nombre, usuario.usuario_id, usuario.usuario_nombre, usuario.usuario_apellido";

    if(isset($busqueda) && $busqueda != ""){ # si la variable busqueda no esta vacia  Genera la consulta que treaera los categorias y el total de categorias
        $consulta_datos = "SELECT 
                            $campos
                            FROM producto 
                            INNER JOIN categoria ON producto.categoria_id = categoria.categoria_id
                            INNER JOIN usuario ON producto.usuario_id = usuario.usuario_id
                            WHERE 
                                producto.producto_nombre LIKE '%$busqueda%' 
                                OR producto.producto_codigo LIKE '%$busqueda%'
                            ORDER BY producto.producto_nombre ASC LIMIT $inicio,$registros;";

        $consulta_total = "SELECT 
                            COUNT(producto_id)
                            FROM producto
                            WHERE 
                                producto.producto_nombre LIKE '%$busqueda%' 
                                OR producto.producto_codigo LIKE '%$busqueda%';";

    }elseif($categoria_id > 0){ # si la variable producto_id esta definida y es mayor a 0 Genera la consulta que treaera los categorias y el total de categorias
        $consulta_datos=" SELECT $campos 
                            FROM producto INNER JOIN categoria ON producto.categoria_id=categoria.categoria_id 
                            INNER JOIN usuario ON producto.usuario_id=usuario.usuario_id 
                            WHERE producto.categoria_id='$categoria_id' 
                            ORDER BY producto.producto_nombre 
                            ASC LIMIT $inicio,$registros";

        $consulta_total = "SELECT COUNT(producto_id) FROM producto WHERE categoria_id = '$categoria_id';";

    }else{
        $consulta_datos = "SELECT 
                            $campos
                            FROM producto
                            INNER JOIN categoria ON producto.categoria_id = categoria.categoria_id
                            INNER JOIN usuario ON producto.usuario_id = usuario.usuario_id
                            ORDER BY producto.producto_nombre
                            ASC LIMIT $inicio,$registros";

        $consulta_total = "SELECT COUNT(producto_id) FROM producto";
    }

    $conexion = conexion();

    $datos = $conexion->query($consulta_datos); #ejecutamos la consulta de los datos
    $datos = $datos->fetchAll(); #fetchAll() devuelve todos los registros de la consulta en un array asociativo
    $total = $conexion->query($consulta_total); #ejecutamos la consulta del total de registros
    $total = (int) $total->fetchColumn(); #fetchColumn() devuelve el valor de la primera columna de la primera fila del resultado de la consulta

    $Npaginas = ceil($total / $registros); #ceil redondea hacia arriba 

    if($total >= 1 && $pagina <= $Npaginas){ #si hay registros y la pagina es menor o igual a la cantidad de paginas agregamos los registros a la tabla ademas de los numeros de la paginacion
        $contador = $inicio + 1; #contador para mostrar el numero de registro mas uno ya que el inicia en 0
        $pag_inicio = $inicio + 1; 
        foreach($datos as $rows){
            $tabla .= '
                <article class="media">
                    <figure class="media-left">
                        <p class="image is-64x64">';
                if(is_file("./img/productos/".$rows["producto_foto"])){ # verificamos si la foto del producto existe 
                    $tabla .= '<img src="./img/productos/'.$rows["producto_foto"].'">'; # si existe mostramos la foto del producto
                }else{
                    $tabla .= '<img src="./img/producto.png">'; # si no existe mostramos una imagen por defecto
                }
            # agregamos los registro a la tabla en base a los datos obtenidos de la consulta
            $tabla .= '
                        </p>
                    </figure>
                    <div class="media-content">
                        <div class="content">
                            <p>
                                <strong>'.$contador.' - '.$rows["producto_nombre"].'</strong><br>
                                <strong>CODIGO:</strong> '.$rows["producto_codigo"].', 
                                <strong>PRECIO:</strong> $'.$rows["producto_precio"].', 
                                <strong>STOCK:</strong> '.$rows["producto_stock"].', 
                                <strong>CATEGORIA:</strong> '.$rows["categoria_nombre"].', 
                                <strong>REGISTRADO POR:</strong> '.$rows["usuario_nombre"].' '.$rows["usuario_apellido"].'<br>
                            </p>
                        </div>
                        <div class="has-text-right">
                            <a href="index.php?vista=product_img&product_id_up='.$rows["producto_id"].'" class="button is-link is-rounded is-small">Imagen</a>
                            <a href="index.php?vista=product_update&product_id_up='.$rows["producto_id"].'" class="button is-success is-rounded is-small">Actualizar</a>
                            <a href="'.$url.$pagina.'&product_id_del='.$rows["producto_id"].'" class="button is-danger is-rounded is-small">Eliminar</a>
                        </div>
                    </div>
                </article>
                <hr>
            ';
            $contador++;

        }
        $pag_final = $contador - 1;
    }else{
        if($total >= 1){ #si hay registros pero la pagina es mayor a la cantidad de paginas mostramos un mensaje de error
            $tabla .= '
                <p class="has-text-centered">
                    <a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
                        Haga clic acá para recargar el listado
                    </a>
                </p>
            ';
        }else{
            $tabla .= '
                <p class="has-text-centered"> No hay registros en el sistema para mostrar en este momento.</p>
            ';
        }
    }

    #si hay registros y la pagina es menor o igual a la cantidad de paginas mostramos el total de registros y los numeros de la paginacion
    if($total >= 1 && $pagina <= $Npaginas){
        $tabla .= '<p class="has-text-right">Mostrando productos <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
    }

    $conexion = null; #cerramos la conexion a la base de datos
    echo $tabla;

    #si hay registros y la pagina es menor o igual a la cantidad de paginas mostramos los numeros de la paginacion
    if($total >= 1 && $pagina <= $Npaginas){
        echo paginador_tablas($pagina, $Npaginas, $url, 3);
    }