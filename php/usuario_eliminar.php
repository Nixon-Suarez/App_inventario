<?php
    $user_id_del = limpiar_cadena($_GET['user_id_del']);

    // Verificamos usuario
    $check_usuario = conexion();
    $check_usuario = $check_usuario->query("SELECT usuario_id FROM usuario WHERE usuario_id='$user_id_del'");

    if($check_usuario->rowCount() == 1){
        // Verificamos producto
        $check_producto = conexion();
        $check_producto = $check_producto ->query("SELECT usuario_id FROM producto WHERE usuario_id='$user_id_del' LIMIT 1"); 
        if($check_producto->rowCount() == 0){
            // Verificamos usuario
            $eliminar_usuario = conexion();
            $eliminar_usuario = $eliminar_usuario->prepare("DELETE FROM usuario WHERE usuario_id=:id"); #query para consultas y prepare para otras consultas, se utiliza un marcador(:id) para evitar inyecciones SQL
            $eliminar_usuario->execute([":id"=>$user_id_del]); #ejecutamos la consulta y le pasamos el id del usuario a eliminar
            
            if($eliminar_usuario->rowCount() == 1){ #si se elimino el usuario
                echo'
                    <div class="notification is-info is-light">
                        <strong>Usuario eliminado</strong><br> <!-- muestra una alerta o notificacion -->
                        El usuario ha sido eliminado correctamente
                    </div>
                ';
            }else{
                echo'
                    <div class="notification is-danger is-light">
                        <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                        El usuario, No se pudo eliminar, porfavor intente nuevamente
                    </div>
                ';
            }
            $eliminar_usuario = null; #cerramos la conexion a la base de datos
        }else{
            echo'
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                    El usuario ingresado, No se puede eliminar porque tiene productos registrados
                </div>
            ';
        }
        $check_producto = null; 
    }else {
        echo'
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                El usuario ingresado ha eliminar no existe
            </div>
        ';
    }
    $check_usuario = null;
