<?php
    require_once "main.php";

    #Almacenando Datos
    $categoria_nombre = limpiar_cadena($_POST['categoria_nombre']);
    $catergoria_ubicacion = limpiar_cadena($_POST['categoria_ubicacion']);

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
    if($catergoria_ubicacion != "")
        if(verificar_fallos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}", $catergoria_ubicacion)){
            echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                la ubicacion no coincide con el formato solicitado
            </div>';
            exit();
        }

    # Verificando si el nombre del categoria ya existe
    $check_nombre = conexion();
    $check_nombre = $check_nombre->query("SELECT categoria_nombre FROM categoria WHERE categoria_nombre ='$categoria_nombre'");
        if($check_nombre->rowCount()>0){
                echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                    El nombre de la categoria ya se encuentra registrado, por favor elija otro
                </div>';
                exit();
        }
    $check_nombre = null; # cierra la consulta

    
    # INSERTANDO LOS DATOS A LA BASE DE DATOS
    $guardar_categoria = conexion();
    $guardar_categoria = $guardar_categoria->prepare("INSERT INTO categoria
                                                            (categoria_nombre, categoria_ubicacion)  
                                                            VALUES
                                                            (:categoria_nombre, :categoria_ubicacion);"); #query para consultas SELECT y prepare para otras consultas
    // ☝️se usan marcadores esto con el fin de evitar inyecciones SQL

    $marcadores = [
        ":categoria_nombre" => $categoria_nombre,
        ":categoria_ubicacion" => $catergoria_ubicacion
    ];
    // ☝️se asignan los marcadores a los valores que se van a insertar en la base de datos

    $guardar_categoria->execute($marcadores);

    if($guardar_categoria->rowCount()==1){
        echo '
        <div class="notification is-info is-light">
            <strong>Categoria registrada</strong><br> <!-- muestra una alerta o notificacion -->
            La categoria se registro correctamente
        </div>';
    }else{
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            No se ha podido registrar la categoria
        </div>';
    }
    $guardar_categoria=null; # cierra la consulta