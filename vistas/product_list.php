<div class="container is-fluid mb-6">
    <h1 class="title">Productos</h1>
    <h2 class="subtitle">Lista de productos</h2>
</div>

<div class="container pb-6 pt-6">
    <?php
        require_once "php/main.php";
        //eliminar usuario
        if(isset($_GET['product_id_del'])){
            require_once "php/producto_eliminar.php";
        }

        if(!isset($_GET['page'])){
            $pagina = 1;
        }else{
            $pagina = (int) $_GET['page']; #(int) convierte a entero el valor obtenido de la URL
            if($pagina <= 0){
                $pagina = 1;
            }
        }

        $categoria_id = (isset($_GET['category_id'])) ? (int) $_GET['category_id'] : 0; // Obtenemos el id de la categoria si existe, sino es 0

        $pagina = limpiar_cadena($pagina); // Limpiamos la cadena de la URL
        $url = "index.php?vista=product_list&page="; // Separamos la URL en partes
        $registros = 10; // Cantidad de registros por página
        $busqueda = ""; // Variable para buscar registros

        require_once "php/producto_lista.php";
    ?>
</div>