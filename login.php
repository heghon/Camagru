<?php
    require_once "functions.php";

    reconnect_cookie();

    if (isset($_SESSION["auth"])) {
        header("Location: account.php");
        exit();
    }

    if(!empty($_POST["username"]) && !empty($_POST["password"])) {

        require_once "config/db.php";

        $request = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :username AND confirmed_at IS NOT NULL");
        $request->execute(["username" => $_POST["username"]]);
        $user = $request->fetch();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if($user && password_verify($_POST["password"], $user->password)) {
            $_SESSION["auth"] = $user;
            $_SESSION["flash"]["success"] = "Vous êtes bien connecté.";

            $remember_token = str_random(255);
            if ($_POST["remember"]) {
                $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?")->execute($remember_token, $user->id);
                setcookie("remember", "$user->id//$remember_token", time() + 60 * 60 * 24 * 7);
            }

            header("Location: account.php");
            exit();
        }
        else {
            $_SESSION["flash"]["danger"] = "Identifiant ou mot de passe incorrect.";
            header("Location: login.php");
            exit();
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
