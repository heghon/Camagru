<?php
    require_once "config/bootstrap.php";
    App::getAuth()->logout();
    Session::getInstance()->setFlash("success", "Vous êtes bien deconnecté.");
    App::redirect("index.php");