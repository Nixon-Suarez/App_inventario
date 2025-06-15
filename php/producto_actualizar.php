<?php
    require_once '../inc/session_start.php';
    require_once 'main.php';

    $id = limpiar_cadena($_POST['producto_id']);

    // Verificar si el producto existe
    $check_producto = conexion();
    $check_producto = $check_producto->query("SELECT * FROM producto WHERE producto_id = '$id' LIMIT 1");

    if($check_producto -> rowCount() <= 0){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            No se ha podido encontar el producto
        </div>';
        exit();
    }else{
        $datos = $check_producto->fetch();
    }
    $check_producto = null; 

    #Almacenando Datos
    $producto_codigo = limpiar_cadena($_POST['producto_codigo']);
    $producto_nombre = limpiar_cadena($_POST['producto_nombre']);
    $producto_precio = limpiar_cadena($_POST['producto_precio']);
    $producto_stock = limpiar_cadena($_POST['producto_stock']);
    $producto_categoria = limpiar_cadena($_POST['producto_categoria']);

    // verificar campos obligatorios
    if($producto_codigo=="" || $producto_codigo=="" || $producto_precio=="" || $producto_stock=="" || $producto_categoria==""){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>';
        exit();
    }

    #Verificando integridad de los datos
    if(verificar_fallos("[a-zA-Z0-9- ]{1,70}", $producto_codigo)){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br>
            El codigo no coincide con el formato solicitado
        </div>';
        exit();
    }elseif(verificar_fallos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}", $producto_nombre)){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br>
            El nombre no coincide con el formato solicitado
        </div>';
        exit();
    }elseif(verificar_fallos("[0-9.]{1,25}", $producto_precio)){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br>
            El precio no coincide con el formato solicitado
        </div>';
        exit();
    }elseif(verificar_fallos("[0-9.]{1,25}", $producto_stock)){
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br>
            La stock no coincide con el formato solicitado
        </div>';
        exit();
    }

    # Verificando si el codigo de barra ya existe
    if($producto_codigo != $datos['producto_codigo']){
        $check_codigo = conexion();
        $check_codigo = $check_codigo->query("SELECT producto_codigo FROM producto WHERE producto_codigo='$producto_codigo'");
            if($check_codigo->rowCount()>0){
                    echo '
                    <div class="notification is-danger is-light">
                        <strong>Ocurrio un error inesperado</strong><br>
                        El codigo ya se encuentra registrado
                    </div>';
                    exit();
            }
        $check_codigo->closeCursor(); # cierra la consulta
    }

    # Verificando si el nombre ya existe
    if($producto_nombre != $datos['producto_nombre']){
        $check_nombre = conexion();
        $check_nombre = $check_nombre->query("SELECT producto_nombre FROM producto WHERE producto_nombre='$producto_nombre'");
            if($check_nombre->rowCount()>0){
                    echo '
                    <div class="notification is-danger is-light">
                        <strong>Ocurrio un error inesperado</strong><br>
                        el nombre ya se encuentra registrado
                    </div>';
                    exit();
            }
        $check_nombre->closeCursor(); # cierra la consulta
    }

    # Verificando si La categoria ya existe
    if($producto_categoria != $datos['categoria_id']){
        $check_categoria = conexion();
        $check_categoria = $check_categoria->query("SELECT categoria_id FROM categoria WHERE categoria_id='$producto_categoria'");
            if($check_categoria->rowCount()<=0){
                    echo '
                    <div class="notification is-danger is-light">
                        <strong>Ocurrio un error inesperado</strong><br>
                        La categoria no se encuentra registrada
                    </div>';
                    exit();
            }
        $check_categoria->closeCursor();
    }

    # ACTUALIZAR LOS DATOS A LA BASE DE DATOS
    $actualizar_producto = conexion();
    $actualizar_producto = $actualizar_producto->prepare("UPDATE producto SET 
                                                                categoria_id=:categoria_id,
                                                                producto_stock=:producto_stock,
                                                                producto_precio=:producto_precio,
                                                                producto_nombre=:producto_nombre,
                                                                producto_codigo=:producto_codigo
                                                                WHERE producto_id=:id;"); #query para consultas y prepare para otras consultas
    // ☝️se usan marcadores esto con el fin de evitar inyecciones SQL

    $marcadores = [
        ":categoria_id" => $producto_categoria,
        ":producto_stock" => $producto_stock,
        ":producto_precio" => $producto_precio,
        ":producto_nombre" => $producto_nombre,
        ":producto_codigo" => $producto_codigo,
        ":id" => $id
    ];
    // ☝️se asignan los marcadores a los valores que se van a insertar en la base de datos

    if($actualizar_producto->execute($marcadores)){
        echo '
        <div class="notification is-info is-light">
            <strong>producto actualizada</strong><br> <!-- muestra una alerta o notificacion -->
            El producto se actualizo correctamente
        </div>';
    }else{
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            El producto no se pudo actualizar, por favor intente nuevamente
        </div>';
    }
    $actualizar_producto = null; # cierra la consulta
