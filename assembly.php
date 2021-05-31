<?php
    require_once "config/bootstrap.php";
    $session = Session::getInstance();
    $auth = App::getAuth();
    $db = App::getDatabase();
    $picture = new Pictures($session);

    $auth->restrict("restriction_msg_assembly");

    $actualUserPseudo = $auth->actualUser()->username;
    // var_dump($actualUserPseudo);

    $userIDs = $picture->getUserPicsID($db, $actualUserPseudo);
    // var_dump($userIDs);

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
            <section id="assemblyUp">
                <aside id="filterContainer">
                    <h5>Filtres</h5>
                    <div id="filters">
                        <input type="image" onClick="putFilter('1')" class="filterClass" id="filter1" alt="Cadre1" src="/filters/Cadre1.png">
                        <input type="image" onClick="putFilter('2')" class="filterClass" id="filter2" alt="Cadre2" src="/filters/Cadre2.png">
                        <input type="image" onClick="putFilter('3')" class="filterClass" id="filter3" alt="Cadre3" src="/filters/Cadre3.png">
                        <input type="image" onClick="putFilter('4')" class="filterClass" id="filter4" alt="Cadre4" src="/filters/Cadre4.png">
                        <input type="image" onClick="putFilter('5')" class="filterClass" id="filter5" alt="Cadre5" src="/filters/Cadre5.png">
                        <input type="image" onClick="putFilter('6')" class="filterClass" id="filter6" alt="Cadre6" src="/filters/Cadre6.png">
                    </div>
                </aside>
                <div id="picContainer">
                    <!-- <canvas id="canvas"></canvas> -->
                    <img id="outputImage"/>
                    <video autoplay="true" id="videoFeed"></video>
                    <img src="" id="positionnedFilter"/>
                    <div id="webcamButton">
                        <input id="picUp" type="button" value="Say Cheese !"/>
                    </div>
                    <input id="insertImageButton" name="userImage" type="file" accept="image/*" onchange="preview_image(event)">
                </div>
                <aside id="picResultZone">
                    <h5>Résultats</h5>
                    <div id="picResults">
                        <?php
                        for ($i = 0; $i < count($userIDs); $i++) {
                            echo ('<button class="deleteImageButton" onclick="deleteImage(' . $userIDs[count($userIDs) - 1 - $i] . ')">X</button>
                                    <img class="assemblyResultImage" src="image.php?id=' . $userIDs[count($userIDs) - 1 - $i] . '"/>');
                        };
                        ?>
                    </div>
                </aside>
            </section>
            
        <?php require_once 'elements/footer.php'?>
        <script src="javascript/filterHandler.js"></script>
        <script src="javascript/webcamHandler.js"></script>
        <script src="javascript/pictureDeleter.js"></script>
    </body>
</html>
