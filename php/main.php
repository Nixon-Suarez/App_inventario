<?php
//? en este archivo se encuentran todas las Funciones que se repiten mas de una vez
    // conexion BD
    function conexion(){
                #⬇️intacia de una clase PDO que me permite hacer petisiones a la base de datos y conectarme a ella
        $pdo = new PDO('mysql:host=localhost;dbname=aplicacioninventario', 'root','');
        return $pdo;
    }

    // $pdo -> query("Insert into categoria (categoria_nombre, categoria_ubicacion) values ('prueba', 'texto_ubica')");

    function verificar_fallos($filtro, $cadena) {
        // si la cadena coincide con el filtro retorna falso de lo contrario verdadero
        if(preg_match("/^". $filtro ."$/", $cadena)){
            return false;
        }else{
            return true;
        }
    }

    // $nombre = "Carlos7";

    //                           // ⬇️ expresion regular = de la a - z, de la A - Z, de 6 a 10 caracteres
    // if(verificar_fallos("^[a-zA-Z]{6,10}$", $nombre)){
    //     echo "Nombre incorrecto";
    // }else {
    //     echo "Nombre correcto";
    // }

    #limapia cadenas de texto (evita inyecciones SQL)
    function limpiar_cadena($cadena){
        $cadena = trim($cadena); //quita espacios en blanco
        $cadena = stripslashes($cadena); //quita barras invertidas
        $cadena = str_ireplace("<script>", "", $cadena); // ireolace = reemplaza una cadena por otra (case-insensitive) en este caso evita inyeccion de javascript
        $cadena = str_ireplace("</script>", "", $cadena); // en este caso evita inyeccion de javascript
        $cadena = str_ireplace("<script src>", "", $cadena); // en este caso evita inyeccion de javascript
        $cadena = str_ireplace("<script type=", "", $cadena); // en este caso evita inyeccion de javascript
        $cadena = str_ireplace("SELECT * FROM", "", $cadena); // en este caso evita inyeccion SQL
        $cadena = str_ireplace("DELETE FROM", "", $cadena); // en este caso evita inyeccion SQL
        $cadena = str_ireplace("INSERT INTO", "", $cadena); // en este caso evita inyeccion de SQL
        $cadena = str_ireplace("DROP TABLE", "", $cadena); // en este caso evita inyeccion de SQL
        $cadena = str_ireplace("DROP DATABASE", "", $cadena); // en este caso evita inyeccion de SQL
        $cadena = str_ireplace("TRUNCATE TABLA", "", $cadena); // en este caso evita inyeccion de SQL
        $cadena = str_ireplace("SHOW TABLES;", "", $cadena); // en este caso evita inyeccion de SQL
        $cadena = str_ireplace("SHOW DATABASE;", "", $cadena); // en este caso evita inyeccion de SQL
        $cadena = str_ireplace("<?php", "", $cadena); // en este caso evita inyeccion de php
        $cadena = str_ireplace("?>", "", $cadena); // en este caso evita inyeccion de php
        $cadena = str_ireplace("--", "", $cadena); // en este caso evita inyeccion de codigo
        $cadena = str_ireplace("^", "", $cadena); // en este caso evita inyeccion de codigo
        $cadena = str_ireplace("<", "", $cadena); // en este caso evita inyeccion de codigo
        $cadena = str_ireplace(">", "", $cadena); // en este caso evita inyeccion de codigo
        $cadena = str_ireplace("[", "", $cadena); // en este caso evita inyeccion de codigo
        $cadena = str_ireplace("]", "", $cadena); // en este caso evita inyeccion de codigo
        $cadena = str_ireplace("==", "", $cadena); // en este caso evita inyeccion de codigo
        $cadena = str_ireplace(";", "", $cadena); // en este caso evita inyeccion de codigo
        $cadena = str_ireplace("::", "", $cadena); // en este caso evita inyeccion de codigo
        $cadena = htmlspecialchars($cadena); //convierte caracteres especiales en entidades HTML
        $cadena = trim($cadena); 
        $cadena = stripslashes($cadena);
        return $cadena;
    }

    // $texto = " HOla mundo <script> alert('hola') </script> ";
    // echo limpiar_cadena($texto);

    #funcion para renombrar fotos
    function renombrar_fotos($nombre){
        $nombre = str_ireplace(" ", "_", $nombre);
        $nombre = str_ireplace("/", "_", $nombre);
        $nombre = str_ireplace("#", "_", $nombre);
        $nombre = str_ireplace("-", "_", $nombre);
        $nombre = str_ireplace("$", "_", $nombre);
        $nombre = str_ireplace(".", "_", $nombre);
        $nombre = str_ireplace(",", "_", $nombre);
        $nombre = $nombre . "_" . rand(1, 1000); //añade un numero aleatorio al final del nombre
        return $nombre;
    }

    // $nombre = "foto de prueba - , / ";
    // echo renombrar_fotos($nombre);

    // funcion paginador 
    function paginador_tablas($pagina, $total_paginas, $url, $botones){
        $tabla = '<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';

        if($pagina <= 1){ # si la pagina es menor o igual a 1 se desactiva el boton de anterior
            $tabla .= '
            <a class="pagination-previous is-disabled" disabled> Anterior</a>
            <ul class="pagination-list">
            ';   
        }else{ # si la pagina es mayor a 1 se activa el boton de anterior
            $tabla .= '
            <a class="pagination-previous" href="'.$url.($pagina-1).'">Anterior</a>
            <ul class="pagination-list">
                <li><a class="pagination-link" href="'.$url.'.1">1</a></li>
                <li><span class="pagination-elliosis">&hellip;</span></li>
            '; 
        }

        $ContadorI = 0;
        for($i = $pagina; $i <= $total_paginas && $ContadorI < $botones; $i++) { # se inicia un ciclo for que va desde la pagina actual hasta la cantidad de paginas y se limita a la cantidad de botones
            $tabla .= '<li><a class="pagination-link" href="'.$url.$i.'">'.$i.'</a></li>'; # se crea un boton con el numero de la pagina y se le asigna la url correspondiente
            $ContadorI++;
        }
        
        if($pagina == $total_paginas){ # si la pagina es igual a la cantidad de paginas se desactiva el boton de siguiente
            $tabla .= '
            </ul>
            <a class="pagination-next" is-disabled disabled>Siguiente</a>
            ';   
        }else{ # si la pagina es menor a la cantidad de paginas se activa el boton de siguiente
            $tabla .= '
                <li><span class="pagination-elliosis">&hellip;</span></li>
                <li><a class="pagination-link" href="'.$url.$total_paginas.'">'
                .$total_paginas.'</a></li>
            </ul>
            <a class="pagination-next" href="'.$url.($pagina+1).'">Siguiente</a>
            '; 
        }

        $tabla .= '</nav>';
        return $tabla;
    }