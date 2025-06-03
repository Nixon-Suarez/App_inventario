<?php
    $category_id_del = limpiar_cadena($_GET['category_id_del']);

    // Verificamos categoria
    $check_categoria = conexion();
    $check_categoria = $check_categoria->query("SELECT categoria_id FROM categoria WHERE categoria_id='$category_id_del'");

    if($check_categoria->rowCount() == 1){
        // Verificamos producto
        $check_producto = conexion();
        $check_producto = $check_producto ->query("SELECT categoria_id FROM producto WHERE categoria_id='$category_id_del' LIMIT 1"); 
        if($check_producto->rowCount() == 0){
            // Verificamos categoria
            $eliminar_categoria = conexion();
            $eliminar_categoria = $eliminar_categoria->prepare("DELETE FROM categoria WHERE categoria_id=:id"); #query para consultas y prepare para otras consultas, se utiliza un marcador(:id) para evitar inyecciones SQL
            $eliminar_categoria->execute([":id"=>$category_id_del]); #ejecutamos la consulta y le pasamos el id del categoria a eliminar
            
            if($eliminar_categoria->rowCount() == 1){ #si se elimino el categoria
                echo'
                    <div class="notification is-info is-light">
                        <strong>categoria eliminado</strong><br> <!-- muestra una alerta o notificacion -->
                        El categoria ha sido eliminado correctamente
                    </div>
                ';
            }else{
                echo'
                    <div class="notification is-danger is-light">
                        <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                        El categoria, No se pudo eliminar, porfavor intente nuevamente
                    </div>
                ';
            }
            $eliminar_categoria = null; #cerramos la conexion a la base de datos
        }else{
            echo'
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                    El categoria ingresado, No se puede eliminar porque tiene productos registrados
                </div>
            ';
        }
        $check_producto = null; 
    }else {
        echo'
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                El categoria ingresado ha eliminar no existe
            </div>
        ';
    }
    $check_categoria = null; 