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
                <button>Start streaming</button>
                <div class="video_wrap">
                    <video id="video" playsinline autoplay muted></video>
                </div>
                <div class="controller">
                    <button id="snap">Prendre une photo</button>
                </div>
                <canvas id="canvas"></canvas>
                <img src="http://placekitten.com/g/320/261" id="photo" alt="photo">
                
        <?php require_once 'elements/footer.php'?>
        <script src="javascript/webcamHandler.js"></script>
    </body>
</html>
