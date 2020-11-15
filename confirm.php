<?php


    $user_id = $_GET["id"];
    $token = $_GET["token"];

    require_once "config/db.php";

    $request = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $request->execute([$user_id]);
    $user = $request->fetch();
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if ($user && $user->confirmation_token === $token) {
        $pdo->prepare("UPDATE users SET confirmation_token = NULL, confirmed_at = NOW(), id = ?")->execute([$user_id]);
        $_SESSION["auth"] = $user;
        $_SESSION["flash"]["success"] = "Votre compte a bien été validé";
        header("Location: account.php");
    } else {
        $_SESSION["flash"]["danger"] = "Ce token n'est plus valide";
        header("Location: login.php");
    }


?>