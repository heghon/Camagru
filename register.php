<?php

require_once("functions.php");

if(!empty($_POST)){

    $errors = array();

    if(empty($_POST["username"]) || !preg_match('@[A-Za-z0-9_-]@', $_POST["username"])){
        $errors["username"] = "Votre nom d'utilisateur n'est pas valide - utilisez seulement des caractères alphanumériques, underscore et tiret";
    }

    if (empty($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
        $errors["email"] = "Votre email n'est pas valide";
    }

    $uppercase = preg_match('@[A-Z]@', $_POST["password"]);
    $lowercase = preg_match('@[a-z]@', $_POST["password"]);
    $number    = preg_match('@[0-9]@', $_POST["password"]);

    if(!$uppercase || !$lowercase || !$number || strlen($_POST["password"]) < 8) {
        $errors["password"] = "Vous devez rentrer un mot de passe valide : il doit contenir 8 caractères minimum, un nombre, une majuscule et une minuscule";
    }

    if ($_POST["password"] !== $_POST["password-confirm"]){
        $errors["password-confirm"] = "Vous devez confirmer correctement votre mot de passe";
    }

     phpinfo();
    debug($errors);

}
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/index.css" />
    <title>Am'Stram'Gram</title>
</head>
<body>
    <?php require_once 'elements/header.php'; ?>
    <div class="content">
        <h1>S'inscrire</h1>

        <form action="" method="POST">
            <div class="form-group">
                <label for="">Nom d'utilisateur</label>
                <input type="text" name="username" />
            </div>

        <form action="" method="POST">
            <div class="form-group">
                <label for="">Adresse mail</label>
                <input type="text" name="email" />
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
