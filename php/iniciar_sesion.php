<?php   
    #Almacenar Datos
    $usuario = limpiar_cadena($_POST['login_usuario']);
    $clave = limpiar_cadena($_POST['login_clave']);

    // verificar campos obligatorios
    if($usuario=="" || $clave==""){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            No has llenado todos los campos que son obligatorios
        </div>';
        exit();
    }

    #Verificando integridad de los datos
    if(verificar_fallos("^[a-zA-Z0-9]{4,20}$", $usuario)){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            El usuario no coincide con el formato solicitado
        </div>';
        exit();
    }elseif(verificar_fallos("^[a-zA-Z0-9$@.-]{7,100}$", $clave)){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            La clave no coincide con el formato solicitado
        </div>';
        exit();
    }

    # Verificando si el usuario es valido
    $check_user = conexion();
    $check_user = $check_user->query("SELECT * FROM usuario WHERE usuario_usuario='$usuario'");
        if($check_user->rowCount()==1){
            $check_user = $check_user->fetch(); # fetch devuelve un array asociativo de la consulta
            if($check_user['usuario_usuario'] == $usuario && password_verify($clave, $check_user['Clave_cript'])){ #Valida si concide la clave encriptada con la que se guardo en el array y que el usuario se igual
                $_SESSION['id'] = $check_user['usuario_id']; #almacena el id del usuario en la sesion
                $_SESSION['nombre'] = $check_user['usuario_nombre']; #almacena el nombre del usuario en la sesion
                $_SESSION['apellido'] = $check_user['usuario_apellido']; #almacena el apellido del usuario en la sesion
                $_SESSION['usuario'] = $check_user['usuario_usuario']; #almacena el usuario en la sesion

                if(headers_sent()){
                    echo "<script>window.location.href='index.php?vista=home';</script>";
                }else{
                    header("Location: index.php?vista=home"); # redirige a la pagina de inicio
                }

            }else{
                echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                    El usuario o clave incorrecta
                </div>';
                exit();
            }
        }else{
            echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                    El usuario o clave incorrecta
                </div>';
                exit();
        }
    $check_user=null;