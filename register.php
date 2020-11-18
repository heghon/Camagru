<?php

require_once "config/bootstrap.php";

if(!empty($_POST)){
    $errors = array();

    $db = App::getDatabase();

    $validator = new Validator($_POST);
    $validator->usernameValidator($db, "users");
    $validator->emailValidator($db, "users");
    $validator->passwordValidator();

    if ($validator->isValid()) {
        App::getAuth()->register($db, $_POST["username"], $_POST["email"], $_POST["password"]);
        Session::getInstance()->setFlash("success", "Un email vous a été envoyé pour valider votre compte.");
        App::redirect("index.php");
    }
    else {
        $errors = $validator->getErrors();
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
                    <input type="password" name="password_confirm" />
                </div>

                <button type="submit">Inscription</button>

            </form>
        </div>
        <?php require_once 'elements/footer.php'?>
    </body>
</html>
