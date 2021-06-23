<?php
require_once "config/bootstrap.php";
if(!empty($_POST["email"])) {
    $db = App::getDatabase();
    $auth = App::getAuth();
    $session = Session::getInstance();
    if($auth->rebootPassword($db, htmlentities($_POST["email"], ENT_QUOTES))) {
        $session->setFlash("success", "Un email vous a été envoyé pour votre nouveau mot de passe.");
        App::redirect("login.php");
    }
    else {
        $session->setFlash("danger", "Désolé, aucun compte ne correspond à cette adresse mail.");
        App::redirect("forget.php");
    }
}
/*
    if(!empty(htmlentities($_POST["email"], ENT_QUOTES))) {
        require_once "functions.php";
        require_once "config/db.php";
        $request = $pdo->prepare("SELECT * FROM users WHERE email = :email AND confirmed_at IS NOT NULL");
        $request->execute(["email" => htmlentities($_POST["email"], ENT_QUOTES)]);
        $user = $request->fetch();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if($user) {
            $reset_token = str_random(60);
            $pdo->prepare("UPDATE users SET reset_token = ?, reseted_at = NOW() WHERE id = ?")->execute([$reset_token, $user->id]);

            mail(htmlentities($_POST["email"], ENT_QUOTES), "Am'Stram'Gram - redéfinition de votre mot de passe", "Bonjour, vous avez demandé à changer votre mot de passe.\r\nPour confirmer votre nouveau mot de passe, veuillez cliquer sur le lien :\r\nhttp://camagru/reset.php?id={$user->id}&token=$reset_token");

            $_SESSION["flash"]["success"] = "Un email vous a été envoyé pour votre nouveau mot de passe.";
            header("Location: login.php");
            exit();
        }
        else {
            $_SESSION["flash"]["danger"] = "Aucun compte ne correspond à cette adresse mail.";
            header("Location: forget.php");
            exit();
        }
    }
    */
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/content.css" />
    </head>
    <body>
        <?php require_once 'elements/header.php'; ?>
            <div class="content">
                <h1 class="page-title">Mot de passe oublié</h1>
                <div class="form-div">
                    <form action="" method="POST" class="account-form">
                        <div class="form-group">
                            <label for="">adresse email</label>
                            <input type="email" name="email" />
                        </div>

                        <button type="submit">Envoyer</button>

                    </form>
                </div>
            </div>
        <?php require_once 'elements/footer.php'?>
    </body>
</html>