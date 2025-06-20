<?php require "./inc/session_start.php"; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php include "./inc/head.php"; ?>
</head>

<body>
    <?php
    if (!isset($_GET['vista']) || $_GET['vista'] == "") {
        $_GET['vista'] = "login";
    }

    if (is_file("./vistas/" . $_GET['vista'] . ".php") && $_GET['vista'] != "login" && $_GET['vista'] != "404") {
        
        # Cerrar sesión forzadamente
        if((!isset($_SESSION['id']) || $_SESSION['id'] == "") || (!isset($_SESSION['usuario']) || $_SESSION['usuario'] == "")){
            include "./vistas/logout.php";
            exit();
        }

        include "./inc/navbar.php";
        include "./vistas/" . $_GET['vista'] . ".php";
        include "./inc/script.php";
    } else {
        if ($_GET['vista'] == "login") {
            include "./vistas/login.php";
        } else {
            http_response_code(404);
            include "./vistas/404.php";
        }
    }
    ?>
</body>

</html>