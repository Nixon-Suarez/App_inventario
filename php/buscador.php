<?php 
    $modulo_buscador = limpiar_cadena($_POST['modulo_buscador']); 

    $modulos = ["usuario", "categoria", "producto"]; // Modulos disponibles para busqueda

    if(in_array($modulo_buscador, $modulos)){
        $modulos_url = [
            "usuario" => "user_search",
            "categoria" => "category_search",
            "producto" => "product_search"
        ];
        $modulo_url = $modulos_url[$modulo_buscador]; // Se obtiene el modulo correspondiente a la busqueda
        $modulo_buscador = "busqueda_".$modulo_buscador; // Se crea la variable de busqueda correspondiente al modulo el cual se utiliza para validar la variable de session
        
        // Iniciar busqueda (definimos la variable de session)
        if(isset($_POST['txt_buscador'])){
            $txt = limpiar_cadena($_POST['txt_buscador']); // Limpiamos la cadena de busqueda
            
            if($txt == ""){ #Si la cadena de busqueda esta vacia muestra un mensaje de error
                echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                    Introduzca un termino de busqueda
                </div>';
            }else{ # Si no valida que siga los parametros de busqueda
                if(verificar_fallos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}", $txt)){
                    echo'<div class="notification is-danger is-light">
                        <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
                        El termino de busqueda no es valido
                    </div>';
                }else{ # Si la cadena de busqueda es valida, se crea la variable de session y se redirige a la pagina correspondiente la cual muestra los resultados de la busqueda
                    $_SESSION[$modulo_buscador] = $txt; 
                    header("Location: index.php?vista=$modulo_url",true, 303); // recarga la pagina pero sin enviar el formualrio
                    exit();
                }
            }
        }
        // Eliminar busqueda
        if(isset($_POST['eliminar_buscador'])){
            unset($_SESSION[$modulo_buscador]); // Eliminamos la variable de session
            header("Location: index.php?vista=$modulo_url",true, 303); // recarga la pagina 
            exit(); 
        }
    }else{
        echo '
        <div class="notification is-danger is-light">
            <strong>Ocurrio un error inesperado</strong><br> <!-- muestra una alerta o notificacion -->
            No se puede realizar la busqueda
        </div>';
    }

