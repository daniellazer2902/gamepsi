<?php
    session_start();
    $login = $_SESSION['pseudo'];
    if (is_null($login)) {
        header('Location: ./index.php');
    }
    
// suppresion de la session utile pour la deconnection
    session_destroy();
    header('Location: ./index.php');
