<?php
require_once "config/bootstrap.php";
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/content.css" />
        <title>Am'Stram'Gram</title>
    </head>
    <body>
        <?php require_once 'config/setup.php';?>
        <?php require_once 'elements/header.php'; ?>
            <div class="content" id="index-page">
                <img src="/filters/welcomeSign.png" alt="" id="welcome-sign">
            </div>
        <?php require_once 'elements/footer.php'?>
    </body>
</html>
