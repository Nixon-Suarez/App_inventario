<?php
    require_once '../inc/session_start.php';
    require_once 'main.php';

    $producto_id = limpiar_cadena($_POST['img_up_id']);

    // Verificar si el producto existe
    $check_producto = conexion();
    $check_producto = $check_producto->query("SELECT * FROM producto WHERE producto_id = '$producto_id' LIMIT 1");

    if($check_producto -> rowCount() == 1){
        $datos = $check_producto->fetch();
    }else{
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            No se ha podido encontar el producto
        </div>';
        exit();
    }
    $check_producto = null;

    // Directorio de imagenes
    $img_dir = "../img/productos/";

    // Verificar si la 
    if($_FILES['producto_foto']['name'] == "" || $_FILES['producto_foto']['size'] < 0){
        echo '
            <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br>
            No se ha seleccionado una imagen';
        exit();
    }

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
    
    chmod($img_dir, 0777); // cambia los permisos del directorio a 0777 osea puede leer, escribir y ejecutar
    
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
    
    $img_nombre = renombrar_fotos($datos['producto_nombre']); // renombra la foto con la funcion renombrar_fotos del main
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

    // Elimina la foto anterior si existe
    if(is_file($img_dir . $datos['producto_foto'])) {
        chmod($img_dir . $datos['producto_foto'], 0777);
        unlink($img_dir . $datos['producto_foto']); // elimina la foto anterior
    }

    # Actualiza la base de datos
    $actualizar_img = conexion();
    $actualizar_img = $actualizar_img->prepare("UPDATE producto SET producto_foto=:foto WHERE producto_id=:id;");

    $marcadores = [
        ":foto" => $foto,
        ":id" => $producto_id
    ];
    
    if($actualizar_img->execute($marcadores)){
        echo '
        <div class="notification is-success is-light">
            <strong>Imagen Actualizada correctamente</strong><br>
            La imagen del producto ha sido actualizada exitosamnente, pulse Aceptar para visualizar los cambios.

            <p class="has-text-centered pt-5 pb-5">
                <a href="index.php?vista=product_img&product_id_up='.$producto_id.'" class="button is-success is-rounded" class="button is-link is-rounded">Aceptar</a>
            </p>
        </div>';
    }else{
        if(is_file($img_dir . $foto)){ #valida si la img existe en el directorio
            chmod($img_dir . $foto, 0777);
            unlink($img_dir . $foto); #si existe la elimina
        }
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br>
            Error al subir la imagen, intente nuevamente 
        </div>';
    }