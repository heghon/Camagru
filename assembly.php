<?php
    require_once "config/bootstrap.php";
    $session = Session::getInstance();
    $auth = App::getAuth();
    $db = App::getDatabase();
    $picture = new Pictures($session);

    $auth->restrict("restriction_msg_assembly");

    // SET THE DESTINATION FOLDER
    // $source = $_FILES["upimage"]["tmp_name"];
    // $destination = "uploaded.png";

    // MOVE UPLOADED FILE TO DESTINATION
    // echo move_uploaded_file($source, $destination) ? "OK" : "ERROR UPLOADING";
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/content.css" />
        <title>Am'Stram'Gram photo Booth</title>
    </head>

    <body>
        <?php require_once 'elements/header.php'; ?>
            <div id="assemblyUp">
                <div id="filterContainer">

                </div>
                <div id="picContainer">
                    <img id="outputImage"/>
                    <video autoplay="true" id="videoFeed"></video>
                    <div id="webcamButton">
                        <input id="picUp" type="button" value="Say Cheese !"/>
                    </div>
                    <input type="file" accept="image/*" onchange="preview_image(event)">
                </div>
                <div id="picResultZone">
                    
                </div>
            </div>
            
        <?php require_once 'elements/footer.php'?>
        <script src="javascript/webcamHandler.js"></script>
    </body>
</html>
