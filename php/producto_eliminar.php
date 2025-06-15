<?php
    $product_id_del = limpiar_cadena($_GET['product_id_del']);

    // Verificamos producto
    $check_producto = conexion();
    $check_producto = $check_producto->query("SELECT * FROM producto WHERE producto_id='$product_id_del'");

    if($check_producto->rowCount() == 1){
        // Verificamos producto
        $datos = $check_producto->fetch();
        $eliminar_producto = conexion();
        $eliminar_producto = $eliminar_producto->prepare("DELETE FROM producto WHERE producto_id=:id"); #query para consultas y prepare para otras consultas, se utiliza un marcador(:id) para evitar inyecciones SQL
        $eliminar_producto->execute([":id"=>$product_id_del]); #ejecutamos la consulta y le pasamos el id del producto a eliminar
        
        if($eliminar_producto->rowCount() == 1){ #si se elimino el producto
            if(is_file("img/productos/".$datos['producto_foto'])){ #verificamos si existe la imagen del producto
                chmod("img/productos/".$datos['producto_id'], 0777); #cambiamos los permisos de la imagen a 0777
                unlink("img/productos/".$datos['producto_foto']); #si existe, la eliminamos

            }
            echo'
                <div class="notification is-info is-light">
                    <strong>producto eliminado</strong><br> <!-- muestra una alerta o notificacion -->
                    El producto ha sido eliminado correctamente
                </div>
            ';
        }else{
            echo'
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                    El producto, No se pudo eliminar, porfavor intente nuevamente
                </div>
            ';
        }
        $eliminar_producto = null; #cerramos la conexion a la base de datos
        $check_producto = null; 
    }else {
        echo'
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                El producto ingresado ha eliminar, no existe
            </div>
        ';
    }
    $check_producto = null; 