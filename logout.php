<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    setcookie("remember", NULL, time() - 1);

    unset($_SESSION["auth"]);
    $_SESSION["flash"]["success"] = "Vous êtes bien deconnecté";
    header("Location: index.php");
