<?php
    require_once '../inc/session_start.php';
    require_once 'main.php';

    $producto_id = limpiar_cadena($_POST['img_del_id']);

    // Verificar si el producto existe
    $check_producto = conexion();
    $check_producto = $check_producto->query("SELECT * FROM producto WHERE producto_id = '$producto_id' LIMIT 1");

    if($check_producto -> rowCount() == 1){
        $datos = $check_producto->fetch();
    }else{
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            No se ha podido encontar el producto
        </div>';
        exit();
    }
    $check_producto = null; 

    // Directorio de imagenes
    $img_dir = "../img/productos/";
    chmod($img_dir, 0777); // cambia los permisos del directorio a 0777 osea puede leer, escribir y ejecutar

    if(is_file($img_dir.$datos['producto_foto'])){
        chmod($img_dir, 0777); 
        if(!unlink($img_dir.$datos['producto_foto'])){
            echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado</strong><br>
                Error al eliminar la imagen del producto
            </div>';
            exit();
        }else{
            $eliminar_img = conexion();
            $eliminar_img = $eliminar_img->prepare("UPDATE producto SET producto_foto=:foto WHERE producto_id=:id;");

            $marcadores = [
                ":foto" => "",
                ":id" => $producto_id
            ];

            if($eliminar_img->execute($marcadores)){
                echo '
                <div class="notification is-success is-light">
                    <strong>Imagen eliminada correctamente</strong><br>
                    La imagen del producto ha sido eliminada exitosamnente, pulse Aceptar para visualizar los cambios.

                    <p class="has-text-centered pt-5 pb-5">
                        <a href="index.php?vista=product_img&product_id_up='.$producto_id.'" class="button is-success is-rounded" class="button is-link is-rounded">Aceptar</a>
                    </p>
                </div>';
            }else{
                echo '
                <div class="notification is-warning is-light">
                    <strong>Imagen eliminada</strong><br>
                    Ocurrieron problemas al eliminar la imagen del producto, pero se la imagen del producto ha sido eliminada, pulse Aceptar para visualizar los cambios.

                    <p class="has-text-centered pt-5 pb-5">
                        <a href="index.php?vista=product_img&product_id_up='.$producto_id.'" class="button is-success is-rounded" class="button is-link is-rounded">Aceptar</a>
                    </p>
                </div>';
            }
        }
    }