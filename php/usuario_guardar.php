<?php 
    require_once "main.php";

    #Almacenando Datos
    $usuario_nombre = limpiar_cadena($_POST['usuario_nombre']);
    $usuario_apellido = limpiar_cadena($_POST['usuario_apellido']);
    $usuario = limpiar_cadena($_POST['usuario_usuario']);
    $usuario_email = limpiar_cadena($_POST['usuario_email']);
    $usuario_clave_1 = limpiar_cadena($_POST['usuario_clave_1']);
    $usuario_clave_2 = limpiar_cadena($_POST['usuario_clave_2']);

    // verificar campos obligatorios
    if($usuario_nombre=="" || $usuario_apellido=="" || $usuario=="" || $usuario_clave_1=="" || $usuario_clave_2==""){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            No has llenado todos los campos que son obligatorios
        </div>';
        exit();
    }

    #Verificando integridad de los datos
    if(verificar_fallos("^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}$", $usuario_nombre)){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            El nombre no coincide con el formato solicitado
        </div>';
        exit();
    }elseif(verificar_fallos("^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}$", $usuario_apellido)){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            El apellido no coincide con el formato solicitado
        </div>';
        exit();
    }elseif(verificar_fallos("^[a-zA-Z0-9]{4,20}$", $usuario)){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            El usuario no coincide con el formato solicitado
        </div>';
        exit();
    }elseif(verificar_fallos("^[a-zA-Z0-9$@.-]{7,100}$", $usuario_clave_1)){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            La clave no coincide con el formato solicitado
        </div>';
        exit();
    }
    
    #Verificando si las claves son iguales
    if($usuario_clave_1!=$usuario_clave_2){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            Las claves no coinciden
        </div>';
        exit();
    }else{
        $clave_procesada = password_hash($usuario_clave_1, PASSWORD_BCRYPT, ["cost" => 10]); # encripta la clave
    }
    
    # Verificando si el email es valido y no se encuentra registrado
    if($usuario_email!=""){
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
    }

    # Verificando si el usuario ya existe
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

    # INSERTANDO LOS DATOS A LA BASE DE DATOS
    $guardar_usuario = conexion();
    $guardar_usuario = $guardar_usuario->prepare("INSERT INTO usuario
    (usuario_nombre, usuario_apellido, usuario_usuario, usuario_email, Clave_cript, usuario_clave)  
    VALUES
    (:usuario_nombre, :usuario_apellido, :usuario, :usuario_email, :clave_procesada, :usuario_clave_1);"); #query para consultas SELECT y prepare para otras consultas
    // ☝️se usan marcadores esto con el fin de evitar inyecciones SQL

    $marcadores = [
        ":usuario_nombre" => $usuario_nombre,
        ":usuario_apellido" => $usuario_apellido,
        ":usuario" => $usuario,
        ":usuario_email" => $usuario_email,
        ":clave_procesada" => $clave_procesada,
        ":usuario_clave_1" => $usuario_clave_1
    ];
    // ☝️se asignan los marcadores a los valores que se van a insertar en la base de datos

    $guardar_usuario->execute($marcadores);

    if($guardar_usuario->rowCount()==1){
        echo '
        <div class="notification is-info is-light">
            <strong>Usuario registrado</strong><br> <!-- muestra una alerta o notificacion -->
            El usuario se registro correctamente
        </div>';
    }else{
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            No se ha podido registrar el usuario
        </div>';
    }
    $guardar_usuario=null; # cierra la consulta

