<?php
    if (isset($_GET["id"]) && isset($_GET["token"])) {

        require_once "config/db.php";
        require_once "functions.php";

        $request = $pdo->prepare("SELECT * FROM users WHERE id = ? AND reset_token IS NOT NULL AND reset_token = ? AND reseted_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)");
        $request->execute($_GET["id"], $_GET["token"]);
        $user=$request->fetch();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($user) {

            if (!empty($_POST["password"]) && $_POST["password"] === $_POST["password_confirm"]) {
                $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
                $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL")->execute($password);

                $_SESSION["flash"]["success"] = "Votre mot de passe a bien été modifié";
                $_SESSION["auth"] = $user;
                header("Location: account.php");
                exit();
            }
        }

        else {
            $_SESSION["flash"]["danger"] = "Ce token n'est pas valide.";
            header("Location: login.php");
            exit();
        }
    }

    else {
        header("Location: index.php");
        exit();
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