<?php 
    require_once "main.php";
    require_once "../inc/session_start.php";

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

    # Verificando si el nombre ya existe
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

    # Verificando si La categoria ya existe
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

    // Directorio de imagenes
    $img_dir = "../img/productos/";

    // Validar seleccion de una img
                #⬇️nombre del imput
                                // ⬇️ATTRIBUTO DEL ARCHIVO en este caso el nombre
    if($_FILES['producto_foto']['name'] !="" && $_FILES['producto_foto']['size']>0){
        //  creando directorio si no existe
        if(!file_exists($img_dir)) { 
            if(!mkdir($img_dir, 0777)){ #0777 puede leer, escribir y ejecutar
                echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado</strong><br>
                    Error al crear el directorio de imagenes
                </div>';
                exit();
            }
        }

        // limitar que tipo de archivo esta entrando (se valida con el tipo de mime)
        if(mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/jpeg" && mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/png" && mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/jpg"){
            echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado</strong><br>
                Archivo no permitido, solo se permiten archivos JPG, JPEG y PNG
            </div>';
            exit();
        }
        # limitar el peso del archivo
        if (($_FILES['producto_foto']['size'] / 1024) > 3072) {
            echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado</strong><br>
                Archivo supera el peso permitido, el maximo es 3MB
            </div>';
            exit();
        }
        #Extencion del archivo
        switch(mime_content_type($_FILES['producto_foto']['tmp_name'])) {
            case 'image/jpeg':
                $img_ext = '.jpg'; // si es un archivo jpg
                break;
            case 'image/png':
                $img_ext = '.png'; // si es un archivo png
                break;
            case 'image/jpg':
                $img_ext = '.jpg'; // si es un archivo jpg
                break;
        }

        chmod($img_dir, 0777); // cambia los permisos del directorio a 0777 osea puede leer, escribir y ejecutar

        $img_nombre = renombrar_fotos($producto_nombre); // renombra la foto con la funcion renombrar_fotos del main
        $foto = $img_nombre . $img_ext; // crea la variable foto con el nombre de la foto y la extencion

        // mover la img al directorio de imagenes
        if(!move_uploaded_file($_FILES['producto_foto']['tmp_name'], $img_dir . $foto)) {
            echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado</strong><br>
                Error al subir el archivo, intente nuevamente
            </div>';
            exit();
        }
    }else{
        $foto =""; // si no se selecciona una imagen, se deja la variable foto vacia
    }

    # INSERTANDO LOS DATOS A LA BASE DE DATOS
    $guardar_producto = conexion();
    $guardar_producto = $guardar_producto->prepare("INSERT INTO producto
    (producto_codigo, producto_nombre, producto_precio, producto_stock, producto_foto, categoria_id, usuario_id)  
    VALUES
    (:producto_codigo, :producto_nombre, :producto_precio, :producto_stock, :producto_foto, :producto_categoria, :usuario_id);"); #query para consultas SELECT y prepare para otras consultas
    // ☝️se usan marcadores esto con el fin de evitar inyecciones SQL

    
    $marcadores = [
        ":producto_codigo" => $producto_codigo,
        ":producto_nombre" => $producto_nombre,
        ":producto_precio" => $producto_precio,
        ":producto_stock" => $producto_stock,
        ":producto_foto" => $foto,
        ":producto_categoria" => $producto_categoria,
        ":usuario_id" => $_SESSION['id']# se obtiene el id del usuario que esta logueado
    ];
    // ☝️se asignan los marcadores a los valores que se van a insertar en la base de datos

    $guardar_producto->execute($marcadores);

    if($guardar_producto->rowCount()==1){
        echo '
        <div class="notification is-info is-light">
            <strong>producto registrado</strong><br>
            El producto se registro correctamente
        </div>';
    }else{
        if(is_file($img_dir)){ #valida si la img existe en el directorio
            chmod($img_dir, 0777);
            unlink($img_dir . $foto); #si existe la elimina
        }
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br>
            No se ha podido registrar el producto
        </div>';
    }
    $guardar_producto=null; # cierra la consulta