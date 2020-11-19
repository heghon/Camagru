<?php
    require_once "config/bootstrap.php";
    $auth = App::getAuth();

    $auth->restrict("restriction_msg_assembly");
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/content.css" />
    </head>
    <body>
        <?php require_once 'elements/header.php'; ?>
            <div class="content">
                <h2>Zone de montage</h2>

                <div class="select">
                    <label for="audioSource">Audio source: </label><select id="audioSource"></select>
                </div>

                <div class="select">
                    <label for="videoSource">Video source: </label><select id="videoSource"></select>
                </div>

                <video autoplay muted playsinline></video>
                
        <?php require_once 'elements/footer.php'?>
        <script src="javascript/webcamHandler.js"></script>
    </body>
</html>
