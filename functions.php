<?php

function debug($variable) {
    if (!empty($variable))
        echo "<pre>" . print_r($variable, true) . "</pre>";
}

function str_random($length) {
    $alpha = "0123456789azertyuiopqsdfghjklmwxcvbnAERTYUIOPQSDFGHJKLMWXCVBN";
    return substr(str_shuffle(str_repeat($alpha, $length)), 0, $length);
}

function forbidden_log() {
    if(!isset($_SESSION["auth"])) {
        $_SESSION["flash"]["danger"] = "Vous n'avez pas le droit d'accéder à cette page.";
        header("Location: index.php");
        exit();
    }
}

function reconnect_cookie() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_COOKIE["remember"]) && !isset($_SESSION["auth"])) {

        require_once "config/db.php";

        $parts = explode("//", $_COOKIE["remember"]);
        $user_id = $parts[0];

        $request = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $request->execute($user_id);
        $user = $request->fetch();

        if ($user) {
            if ($_COOKIE["remember"] === "$user_id//$user->remember_token") {
                $_SESSION["auth"] = $user;
                setcookie("remember", $_COOKIE["remember"], time() + 60 * 60 * 24 * 7);

                if ($_SESSION["flash"]) {
                    $_SESSION["flash"] = null;
                }

                header("Location: account.php");
                exit();
            }
            else {
                setcookie("remember", null, time() - 1);
            }
        }
    }

    else {
        setcookie("remember", null, time() - 1);
    }
}