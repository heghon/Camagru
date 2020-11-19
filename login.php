<?php
    require_once "config/bootstrap.php";

    $db = App::getDatabase();

    $auth = App::getAuth();
    $auth->connectFromCookie($db);

    if ($auth->actualUser()) {
        App::redirect("account.php");
    }

    if(!empty($_POST["username"]) && !empty($_POST["password"])) {
        $session = Session::getInstance();
        $user = $auth->login($db, $_POST["username"], $_POST["password"], isset($_POST["remember"]));
        if ($user) {
            $session->setFlash("success", "Vous êtes bien connecté.");
            App::redirect("account.php");
        }
        else {
            $session->setFlash("danger", "Identifiant ou mot de passe incorrect.");
            App::redirect("login.php");
        }
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
                <h1>Connexion</h1>

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
                        <label for="">Nom d'utilisateur ou adresse email</label>
                        <input type="text" name="username" />
                    </div>

                <form action="" method="POST">
                    <div class="form-group">
                        <label for="">Mot de passe <a class="forgotten_password" href="forget.php">(oublié ?)</a></label>
                        <input type="password" name="password" />
                    </div>

                <div class="">
                    <label>
                        <input type="checkbox" name="remember" value="1" id="" />Se souvenir de moi
                    </label>
                </div>

                    <button type="submit">Login</button>

                </form>
            </div>
        <?php require_once 'elements/footer.php'?>
    </body>
</html>
