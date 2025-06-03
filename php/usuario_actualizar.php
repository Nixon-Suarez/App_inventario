<?php
    require_once '../inc/session_start.php';
    require_once 'main.php';

    $id = limpiar_cadena($_POST['usuario_id']);

    // Verificar si el usuario existe
    $check_usuario = conexion();
    $check_usuario = $check_usuario->query("SELECT * FROM usuario WHERE usuario_id = '$id' LIMIT 1");

    if($check_usuario -> rowCount() <= 0){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            No se ha podido encontar el usuario
        </div>';
        exit();
    }else{
        $datos = $check_usuario->fetch();
    }
    $check_usuario = null; 

    $admin_usuario = limpiar_cadena($_POST['administrador_usuario']);
    $admin_clave = limpiar_cadena($_POST['administrador_clave']);
    // verificar campos obligatorios
    if($admin_usuario=="" || $admin_clave==""){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            No has llenado todos los campos que son obligatorios, que corresponden a la cuenta del usuario
        </div>';
        exit();
    }

    #Verificando integridad de los datos
    if(verificar_fallos("[a-zA-Z0-9]{4,20}", $admin_usuario)){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            Su USUARIO no coincide con el formato solicitado
        </div>';
        exit();
    }elseif(verificar_fallos("[a-zA-Z0-9$@.-]{7,100}", $admin_clave)){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            Su CLAVE no coincide con el formato solicitado
        </div>';
        exit();
    }

    #verificando admin
    $check_admin = conexion();
    $check_admin = $check_admin->query("SELECT usuario_usuario, Clave_cript FROM usuario WHERE usuario_usuario = '$admin_usuario' AND usuario_id = '".$_SESSION['id']."' LIMIT 1");
    if($check_admin->rowCount()==1){
        $check_admin = $check_admin->fetch();

        if($check_admin['usuario_usuario']!=$admin_usuario || !password_verify($admin_clave, $check_admin['Clave_cript'])){ # password_verify valida si la clave es la mismo que esta encriptada
            echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                    Usuario o clave  del administrador incorrectos
                </div>';
            exit();
        }
    }else{
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            Usuario o clave  del administrador incorrectos
        </div>';
        exit();
    }
    $check_admin = null;

    #Almacenando Datos
    $usuario_nombre = limpiar_cadena($_POST['usuario_nombre']);
    $usuario_apellido = limpiar_cadena($_POST['usuario_apellido']);
    $usuario = limpiar_cadena($_POST['usuario_usuario']);
    $usuario_email = limpiar_cadena($_POST['usuario_email']);
    $usuario_clave_1 = limpiar_cadena($_POST['usuario_clave_1']);
    $usuario_clave_2 = limpiar_cadena($_POST['usuario_clave_2']);

    // verificar campos obligatorios
    if($usuario_nombre=="" || $usuario_apellido=="" || $usuario==""){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            No has llenado todos los campos que son obligatorios
        </div>';
        exit();
    }

    #Verificando integridad de los datos
    if(verificar_fallos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $usuario_nombre)){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            El nombre no coincide con el formato solicitado
        </div>';
        exit();
    }elseif(verificar_fallos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $usuario_apellido)){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            El apellido no coincide con el formato solicitado
        </div>';
        exit();
    }elseif(verificar_fallos("[a-zA-Z0-9]{4,20}", $usuario)){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            El usuario no coincide con el formato solicitado
        </div>';
        exit();
    }

    # Verificando si el email es valido y no se encuentra registrado
    if($usuario_email != "" && $usuario_email != $datos['usuario_email']){
        if(filter_var( $usuario_email, FILTER_VALIDATE_EMAIL)){ # verifica si el email es valido
            $check_email = conexion();
            $check_email = $check_email->query("SELECT usuario_email FROM usuario WHERE usuario_email='$usuario_email'");
            if($check_email->rowCount()>0){
                echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                    El email ya se encuentra registrado
                </div>';
                exit();
            }
            $check_email->closeCursor(); # cierra la consulta
        }else{
            echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                El email no es valido
            </div>';
            exit();
        }
    }else{
        $usuario_email = $datos['usuario_email'];
    }

    # Verificando si el usuario ya existe
    if($usuario != $datos['usuario_usuario']){
        $check_usuario = conexion();
        $check_usuario = $check_usuario->query("SELECT usuario_usuario FROM usuario WHERE usuario_usuario='$usuario'");
            if($check_usuario->rowCount()>0){
                    echo '
                    <div class="notification is-danger is-light">
                        <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                        El usuario ya se encuentra registrado
                    </div>';
                    exit();
            }
        $check_usuario->closeCursor(); # cierra la consulta
        }
    
    #Verificando si las claves son iguales
    if($usuario_clave_1 != "" || $usuario_clave_2 != ""){
        if(verificar_fallos("[a-zA-Z0-9$@.-]{7,100}", $usuario_clave_1)){
            echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                    La clave no coincide con el formato solicitado
                </div>';
            exit();
        }else{
            if($usuario_clave_1!=$usuario_clave_2){
            echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                Las claves no coinciden
            </div>';
            exit();
            }else{
                $clave_procesada = password_hash($usuario_clave_1, PASSWORD_BCRYPT, ["cost" => 10]); # encripta la clave
                $clave = $usuario_clave_1;
            }
        }
    }else {
        $clave_procesada = $datos['Clave_cript'];
        $clave = $datos['usuario_clave'];
    }

    # ACTUALIZAR LOS DATOS A LA BASE DE DATOS
    $actializar_usuario = conexion();
    $actializar_usuario = $actializar_usuario->prepare("UPDATE usuario SET 
                                                                usuario_nombre=:usuario_nombre,
                                                                usuario_apellido=:usuario_apellido,
                                                                usuario_usuario=:usuario, 
                                                                usuario_email=:usuario_email, 
                                                                Clave_cript=:clave_procesada, 
                                                                usuario_clave=:usuario_clave_1
                                                                WHERE usuario_id=:id;"); #query para consultas y prepare para otras consultas
    // ☝️se usan marcadores esto con el fin de evitar inyecciones SQL

    $marcadores = [
        ":usuario_nombre" => $usuario_nombre,
        ":usuario_apellido" => $usuario_apellido,
        ":usuario" => $usuario,
        ":usuario_email" => $usuario_email,
        ":clave_procesada" => $clave_procesada,
        ":usuario_clave_1" => $clave,
        ":id" => $id
    ];
    // ☝️se asignan los marcadores a los valores que se van a insertar en la base de datos

    if($actializar_usuario->execute($marcadores)){
        echo '
        <div class="notification is-info is-light">
            <strong>Usuario actualizado</strong><br> <!-- muestra una alerta o notificacion -->
            El usuario se Actualizo correctamente
        </div>';
    }else{
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            El usuario se no se pudo actualizar, por favor intente nuevamente
        </div>';
    }
    $actializar_usuario=null; # cierra la consulta