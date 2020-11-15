<?php

require_once "functions.php";
require_once "config/db.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!empty($_POST)){

    $errors = array();

    if(empty($_POST["username"]) || strlen($_POST["username"]) > 255 || !preg_match('@[A-Za-z0-9_-]@', $_POST["username"])){
        $errors["username"] = "Votre nom d'utilisateur n'est pas valide - utilisez seulement des caractères alphanumériques, underscore et tiret";
    } else {
        $request = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $request->execute([$_POST["username"]]);
        $user = $request->fetch();
        if ($user) {
            $errors["username"] = "Ce pseudo est déjà pris";
        }
    }

    if (empty($_POST["email"]) || strlen($_POST["email"]) > 255 || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
        $errors["email"] = "Votre email n'est pas valide";
    } else {
        $request = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $request->execute([$_POST["email"]]);
        $email = $request->fetch();
        if ($email) {
            $errors["email"] = "Cet email est déjà utilisé";
        }
    }

    $uppercase = preg_match('@[A-Z]@', $_POST["password"]);
    $lowercase = preg_match('@[a-z]@', $_POST["password"]);
    $number    = preg_match('@[0-9]@', $_POST["password"]);

    if(!$uppercase || !$lowercase || !$number || strlen($_POST["password"]) > 255 || strlen($_POST["password"]) < 8) {
        $errors["password"] = "Vous devez rentrer un mot de passe valide : il doit contenir 8 caractères minimum, un nombre, une majuscule et une minuscule";
    }

    if ($_POST["password"] !== $_POST["password-confirm"]){
        $errors["password-confirm"] = "Vous devez confirmer correctement votre mot de passe";
    }

    //debug($errors);

    if (empty($errors)) {
        $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
        $request = $pdo->prepare("INSERT INTO users SET username = ?, email = ?, password = ?, confirmation_token = ?");
        $token = str_random(60);
        $request->execute([$_POST["username"], $_POST["email"], $password, $token]);
        $user_id = $pdo->lastInsertId();
        mail($_POST["email"], "Confirmation de votre inscription", "Bonjour et merci de vous être inscrit !\r\nPour confirmer votre inscription, veuillez cliquer sur le lien :\r\nhttp://camagru/confirm.php?id=$user_id&token=$token");
        $_SESSION["flash"]["success"] = "Un email de confirmation vous a été envoyé pour valider votre compte.";
        header("location: login.php");
        exit();
    }


}
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/content.css" />
        <title>Am'Stram'Gram</title>
    </head>
    <body>
        <?php require_once 'elements/header.php'; ?>
        <div class="content">
            <h1>Inscription</h1>

            <?php if (!empty($errors)): ?>
                <div class="alert">
                <p>Certains champs ne sont pas conformes : </p>
                <?php foreach($errors as $error): ?>
                    <li><?= $error; ?></li>
                <?php endforeach ?>   
                </div>
            <?php endif ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="">Nom d'utilisateur</label>
                    <input type="text" name="username" />
                </div>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="">Adresse mail</label>
                    <input type="email" name="email" />
                </div>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="">Mot de passe</label>
                    <input type="password" name="password" />
                </div>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="">Confirmer le mot de passe</label>
                    <input type="password" name="password-confirm" />
                </div>

                <button type="submit">Inscription</button>

            </form>
        </div>
        <?php require_once 'elements/footer.php'?>
    </body>
</html>
