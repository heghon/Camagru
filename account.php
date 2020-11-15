<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once "functions.php";

    forbidden_log();

    if (!empty($_POST["password"])) {
        if ($_POST["password"] != $_POST["password_confirm"]) {
            $_SESSION["flash"]["danger"] = "Le mot de passe est vide ou a mal été validé.";
        } 

        else if (!password_verify($_POST["password"], $_SESSION["auth"]->password)) {

            $uppercase = preg_match('@[A-Z]@', $_POST["password"]);
            $lowercase = preg_match('@[a-z]@', $_POST["password"]);
            $number    = preg_match('@[0-9]@', $_POST["password"]);

            if(!$uppercase || !$lowercase || !$number || strlen($_POST["password"]) > 255 || strlen($_POST["password"]) < 8) {
                $_SESSION["flash"]["danger"] = "Vous devez rentrer un mot de passe valide : il doit contenir 8 caractères minimum, un nombre, une majuscule et une minuscule.";
                header("Location: account.php");
                exit();
            }

            $user_id = $_SESSION["auth"]->id;
            $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
            require_once "config/db.php";
            $request = $pdo->prepare("UPDATE users SET password = ? WHERE id = $user_id")->execute([$password]);
            $_SESSION["flash"]["success"] = "Votre mot de passe a bien été mis à jour.";
        }

        else {
            $_SESSION["flash"]["danger"] = "Votre nouveau mot de passe doit être différent de l'ancien.";
        }
    }

    if (!empty($_POST["username"])) {

        if ($_POST["username"] !== $_SESSION["auth"]->username) {

            if(strlen($_POST["username"]) > 255 || !preg_match('@[A-Za-z0-9_-]@', $_POST["username"])){
                $_SESSION["flash"]["danger"] = "Votre nouveau nom d'utilisateur n'est pas valide - utilisez seulement des caractères alphanumériques, underscore et tiret";
                header("Location: account.php");
                exit();
            }

            else {

                require_once "config/db.php";

                $request = $pdo->prepare("SELECT id FROM users WHERE username = ?");
                $request->execute([$_POST["username"]]);
                $result = $request->fetch();

                if ($result) {
                    $_SESSION["flash"]["danger"] = "Votre nouveau nom d'utilisateur est déjà utilisé.";
                    header("Location: account.php");
                    exit();
                }

                else {
                    $user_id = $_SESSION["auth"]->id;
                    $request = $pdo->prepare("UPDATE users SET username = ? WHERE id = $user_id")->execute([$_POST["username"]]);
                    $_SESSION["flash"]["success"] = "Votre nom d'utilisateur a bien été mis à jour.";

                    $request = $pdo->prepare("SELECT * FROM users WHERE username = ?");
                    $request->execute([$_POST["username"]]);
                    $user = $request->fetch();
                    $_SESSION["auth"] = $user;
                }
            }
        }

        else {
            $_SESSION["flash"]["danger"] = "Votre nouveau nom d'utilisateur doit être différent de l'ancien.";
        }
    }

    if (!empty($_POST["email"])) {

        if ($_POST["email"] !== $_SESSION["auth"]->email) {

            if (strlen($_POST["email"]) > 255 || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
                $_SESSION["flash"]["danger"] = "Votre nouvel email n'est pas valide - utilisez une synthaxe correcte.";
                header("Location: account.php");
                exit();
            }

            else {

                require_once "config/db.php";

                $request = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $request->execute([$_POST["email"]]);
                $result = $request->fetch();

                if ($result) {
                    $_SESSION["flash"]["danger"] = "Cette adresse mail est déjà utilisée, veuillez en prendre une autre.";
                    header("Location: account.php");
                    exit();
                }

                else {
                    $user_id = $_SESSION["auth"]->id;
                    $request = $pdo->prepare("UPDATE users SET email = ? WHERE id = $user_id")->execute([$_POST["username"]]);
                    $_SESSION["flash"]["success"] = "Votre adresse mail a bien été mise à jour.";

                }
            }
        }

        else {
            $_SESSION["flash"]["danger"] = "Votre nouvel email doit être différent de l'ancien.";
        }
    }
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/content.css" />
        <title>Am'Stram'Gram</title>
    </head>
    <?php require "elements/header.php" ?>

        <h2>Bonjour <?= $_SESSION["auth"]->username;?></h2>

        <div>
            <form action="" method="post">
                <div>
                    <input type="password" name="password" placeholder="Nouveau mot de passe" id="">
                </div>
                <div>
                    <input type="password" name="password_confirm" placeholder="Confirmation du nouveau mot de passe" id="">
                </div>
                <button type="submit">Changer de mot de passe</button>
            </form>
        </div>

        <div>
            <form action="" method="post">
                <div>
                    <input type="text" name="username" placeholder="Nouveau nom d'utilisateur" id="">
                </div>
                <button type="submit">Changer de nom d'utilisateur</button>
            </form>
        </div>

        <div>
            <form action="" method="post">
                <div>
                    <input type="text" name="email" placeholder="example@example.fr" id="">
                </div>
                <button type="submit">Changer d'adresse email</button>
            </form>
        </div>
    <?php require "elements/footer.php" ?>
</html>
