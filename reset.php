<?php
require_once "config/bootstrap.php";
    if (isset($_GET["id"]) && isset($_GET["token"])) {
        $db = App::getDatabase();
        $auth = App::getAuth();
        Session::getInstance();
        $user = $auth->checkResetToken($db, $_GET["id"], $_GET["token"]);
        if ($user) {
            $validator = new Validator($_POST);
            $validator->passwordValidator($_POST["password"]);
            if ($validator->isValid()) {
                $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
                $db->query("UPDATE users SET password = ?, reset_token = NULL WHERE id = ?", [$password, $user->id]);
                $session->setFlash("success", "Votre mot de passe a bien été modifié.");
                $auth->connect($user);
                App::redirect("account.php");
            }
        }
        else {
            $session->setFlash("danger", "Désolé, mais ce token n'est pas valide.");
            App::redirect("login.php");
        }
    }
    else {
        App::redirect("index.php");
    }
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/content.css" />
    </head>
    <body>
        <?php require_once 'elements/header.php'; ?>
            <div class="content">
            <h1>Réinitialisation du mot de passe</h1>

                <form action="" method="POST">
                    <div class="form-group">
                        <label for="">Nouveau mot de passe</label>
                        <input type="password" name="password" />
                    </div>

                    <form action="" method="POST">
                    <div class="form-group">
                        <label for="">Confirmation du nouveau mot de passe</label>
                        <input type="password" name="password_confirm" />
                    </div>

                    <button type="submit">Confirmer</button>

                </form>
            </div>
        <?php require_once 'elements/footer.php'?>
    </body>
</html>