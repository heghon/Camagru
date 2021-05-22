<?php
    require_once "config/bootstrap.php";
    $session = Session::getInstance();
    $auth = App::getAuth();
    $db = App::getDatabase();
    $validator = new Validator($_POST);
    if ($auth->actualUser()) {
        $auth->connect($db->query("SELECT * FROM users WHERE id = {$auth->actualUser()->id}")->fetch());
    }
    $user = $auth->actualUser();
    // var_dump($user);
    //die();

    $auth->restrict();

    if (!empty($_POST["password"])) {
        if ($_POST["password"] !== $_POST["password_confirm"]) {
            $session->setFlash("danger", "Le mot de passe a mal été validé.");
            App::redirect("account.php");
        } 

        else if (password_verify($_POST["password"], $user->password)) {
            $session->setFlash("danger", "Votre nouveau mot de passe doit être différent de l'ancien.");
        }

        else if (!password_verify($_POST["password"], $user->password)) {
            $validator->passwordValidator();
            if($validator->isValid()) {
                $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
                $db->query("UPDATE users SET password = ? WHERE id = $user->id", [$password]);
                $session->setFlash("success", "Votre mot de passe a bien été mis à jour.");
                App::redirect("account.php");
            }
            else {
                $session->setFlash("danger", "Vous devez rentrer un mot de passe valide : il doit contenir 8 caractères minimum, un nombre, une majuscule et une minuscule.");
                App::redirect("account.php");
            }
        }
    }

    if (!empty($_POST["username"])) {
        if ($_POST["username"] !== $user->username) {
            $validator->usernameValidator($db, "users");
            if($validator->getErrors()["username"] === "Ce pseudo est déjà pris.") {
                $session->setFlash("danger", "Ce nom d'utilisateur est déjà utilisé, merci d'en choisir un autre.");
                App::redirect("account.php");
            }
            else if ($validator->getErrors()["username"] === "Votre nom d'utilisateur n'est pas valide : il doit faire plus que 3 caractères et n'utilisez seulement que des caractères alphanumériques, underscore et tiret.") {
                $session->setFlash("danger", "Votre nouveau nom d'utilisateur n'est pas valide : il doit faire plus que 3 caractères et n'utilisez seulement que des caractères alphanumériques, underscore et tiret.");
                App::redirect("account.php");
            }
            else {
                $db->query("UPDATE users SET username = ? WHERE id = $user->id", [$_POST["username"]]);
                $session->setFlash("success", "Votre nom d'utilisateur a bien été mis à jour.");
                App::redirect("account.php");
            }
        }
        else {
            $session->setFlash("danger", "Votre nouveau nom d'utilisateur doit être différent de l'ancien.");
            App::redirect("account.php");
        }
    }

    if (!empty($_POST["email"])) {
        if ($_POST["email"] !== $user->email) {
            $validator->emailValidator($db, "users");
            if ($validator->isValid()) {
                $used = $db->query("SELECT id FROM users WHERE email = ?", [$_POST["email"]])->fetch();
                if ($used) {
                    $session->setFlash("danger", "Cette adresse mail est déjà utilisée, veuillez en prendre une autre.");
                    App::redirect("account.php");
                }
                else {
                    $db->query("UPDATE users SET email = ? WHERE id = $user->id", [$_POST["email"]]);
                    $session->setFlash("success", "Votre adresse mail a bien été mise à jour.");
                    App::redirect("account.php");
                }
            }
            else {
                $session->setFlash("danger", "Votre nouvel email n'est pas valide - utilisez une synthaxe correcte.");
                App::redirect("account.php");
            }
        }
        else {
            $session->setFlash("danger", "Votre nouvelle adresse email doit être différente de l'ancienne.");
            App::redirect("account.php");
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

        <h2>Bonjour <?= $user->username;?></h2>

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

    <?php require "elements/footer.php" ?>
</html>
