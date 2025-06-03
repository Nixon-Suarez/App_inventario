<?php
    require_once '../inc/session_start.php';
    require_once 'main.php';

    $id = limpiar_cadena($_POST['categoria_id']);

    // Verificar si la categoria existe
    $check_categoria = conexion();
    $check_categoria = $check_categoria->query("SELECT * FROM categoria WHERE categoria_id = '$id' LIMIT 1;");

    if($check_categoria -> rowCount() <= 0){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            No se ha podido encontrar la categoria
        </div>';
        exit();
    }else{
        $datos = $check_categoria->fetch();
    }
    $check_categoria = null; 

    #Almacenando Datos
    $categoria_nombre = limpiar_cadena($_POST['categoria_nombre']);
    $categoria_ubicacion = limpiar_cadena($_POST['categoria_ubicacion']);

    // verificar campos obligatorios
    if($categoria_nombre==""){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            No has llenado todos los campos que son obligatorios
        </div>';
        exit();
    }

    #Verificando integridad de los datos
    if(verificar_fallos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}", $categoria_nombre)){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            El nombre no coincide con el formato solicitado
        </div>';
        exit();
    }
    if($categoria_ubicacion != "")
        if(verificar_fallos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}", $categoria_ubicacion)){
            echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                la ubicacion no coincide con el formato solicitado
            </div>';
            exit();
        }

    # Verificando si el nombre del categoria ya existe
    if ($categoria_nombre != $datos['categoria_nombre']){ // si el nombre de la categoria es diferente al que ya existe
        $check_nombre = conexion();
        $check_nombre = $check_nombre->query("SELECT categoria_nombre FROM categoria WHERE categoria_nombre = '$categoria_nombre'");
            if($check_nombre->rowCount()>0){
                    echo '
                    <div class="notification is-danger is-light">
                        <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                        El nombre de la categoria ya se encuentra registrado, por favor elija otro
                    </div>';
                    exit();
            }
        $check_nombre = null; # cierra la consulta
    }

    # ACTUALIZAR LOS DATOS A LA BASE DE DATOS
    $actualizar_categoria = conexion();
    $actualizar_categoria = $actualizar_categoria->prepare("UPDATE categoria SET 
                                                                categoria_nombre=:categoria_nombre,
                                                                categoria_ubicacion=:categoria_ubicacion
                                                                WHERE categoria_id=:id;"); #query para consultas y prepare para otras consultas
    // ☝️se usan marcadores esto con el fin de evitar inyecciones SQL

    $marcadores = [
        ":categoria_nombre" => $categoria_nombre,
        ":categoria_ubicacion" => $categoria_ubicacion,
        ":id" => $id
    ];
    // ☝️se asignan los marcadores a los valores que se van a insertar en la base de datos

    if($actualizar_categoria->execute($marcadores)){
        echo '
        <div class="notification is-info is-light">
            <strong>categoria actualizada</strong><br> <!-- muestra una alerta o notificacion -->
            El categoria se actualizo correctamente
        </div>';
    }else{
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            La categoria no se pudo actualizar, por favor intente nuevamente
        </div>';
    }
    $actualizar_categoria = null; # cierra la consulta