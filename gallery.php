<?php
    require_once "config/bootstrap.php";
    $session = Session::getInstance();
    $auth = App::getAuth();
    $db = App::getDatabase();
    $picture = new Pictures($session);
    $comments = new Comments($session);
    $likes = new Likes($session);

    $actualUserPseudo = $auth->isSomeoneHere() ? $auth->actualUser()->username : NULL;

    if ($_POST && $auth->isSomeoneHere()) {
        foreach ($_POST as $key => $data) {
            $picAuthorID = $auth->getUserID($db, $picture->getAuthor($db, $key));
            $commentValidation = $auth->checkCommentToken($db, $picAuthorID);
            if (is_numeric($key) && is_string(htmlentities($data, ENT_QUOTES))) {
                $comments->uploadComment($db, $key, $picture->getAuthor($db, $key), $auth->getUserEmail($db, $picture->getAuthor($db, $key)) ,$auth->checkCommentToken($db, $picAuthorID) ,$actualUserPseudo, $data);
            }
            else if ($key == "likeAPic" && is_numeric(htmlentities($data, ENT_QUOTES))) {
                $likes->putLike($db, htmlentities($data, ENT_QUOTES), $auth->getUserID($db, $actualUserPseudo));
            }
            else if ($key == "dislikeAPic" && is_numeric(htmlentities($data, ENT_QUOTES))) {
                $likes->removeLike($db, htmlentities($data, ENT_QUOTES), $auth->getUserID($db, $actualUserPseudo));
            }
        }
    }

    $commentsIDs = $comments->getCommentsIDs($db);
    $picturesIDs = array_reverse($picture->getPicsIDs($db));
    $nbrPictures = count($picturesIDs);

    $byPage = 5;
    $nbrPages = ceil($nbrPictures / $byPage);

    if(null !== htmlentities($_GET['page'], ENT_QUOTES) && !empty($_GET['page']) && is_numeric(htmlentities($_GET['page'], ENT_QUOTES))
        && htmlentities($_GET['page'], ENT_QUOTES) <= $nbrPages && htmlentities($_GET['page'], ENT_QUOTES) > 0){
        $currentPage = (int) strip_tags(htmlentities($_GET['page'], ENT_QUOTES));
    }else{
        $currentPage = 1;
    }

    $first = ($currentPage * $byPage) - $byPage;
    // var_dump($likes->checkLike($db, $pictureID, $userID));

    function printPics($auth, $comments, $picture, $db, $picturesIDs, $commentsIDs, $first, $byPage, $currentPage, $likes, $actualUserPseudo) {
        $limit = $first + $byPage < count($picturesIDs) ? $first + $byPage : count($picturesIDs);
        for ($i=$first; $i < $limit; $i++) {
            $actualComments = "";
            for ($j = 0; $j < count($commentsIDs); $j++) {
                if ($comments->getPictureID($db, $commentsIDs[$j]) == $picturesIDs[$i]) {
                    $actualComments = $actualComments . '<div class="commentPseudo">' . $comments->getAuthor($db, $commentsIDs[$j]) . '</div>' . 
                                        '<div class="commentComment">' . htmlentities($comments->getComment($db, $commentsIDs[$j]), ENT_QUOTES) . '</div>';
                }
            };
            if ($auth->isSomeoneHere()) {
                if ($likes->checkLike($db, $picturesIDs[$i], $auth->getUserID($db, $actualUserPseudo))) {
                    $thumb = 
                        '<form method="post" class="thumbForm" action="/gallery.php?page=' . $currentPage . '">
                            <button type="submit" value="' . $picturesIDs[$i] . '" class="thumbUp" name="dislikeAPic" title="like"><img class="thumbPic" src="/filters/thumbDown.png" alt="" /></button>
                        </form>';
                }
                else {
                    $thumb = 
                        '<form method="post" class="thumbForm" action="/gallery.php?page=' . $currentPage . '">
                            <button type="submit" value="' . $picturesIDs[$i] . '" class="thumbUp" name="likeAPic" title="like"><img class="thumbPic" src="/filters/thumbUp.png" alt="" /></button>
                        </form>';
                }
                $comPlace = '
                <form method="post" action="/gallery.php?page=' . $currentPage . '">
                    <p>
                        <label>Commentaire</label>: <input type="text" maxlength="254" name="' . $picturesIDs[$i] . '" class="commentTextBar" required placeholder="Ex : Quelle belle photo !"/>
                        <input type="submit" value="Envoyer" />
                    </p>
                </form>
                ';
            }
            else {
                $thumb = "";
                $comPlace = "";
            }
            if ($picturesIDs[$i]) {
                echo ('
                <div id="pic' . $picturesIDs[$i] . '" class="galleryBox">
                    <img src="/image.php?id=' . $picturesIDs[$i] . '" class="galleryPic" alt="">
                    <div class="galleryInfoBox">
                        <p>De : ' . $picture->getAuthor($db, $picturesIDs[$i]) . '  |  Likes : ' . $likes->countLikes($db, $picturesIDs[$i]) . ' </p>
                        ' . $thumb . '
                        <div class="commentBox">
                        ' . $actualComments . '
                        </div>
                        ' . $comPlace . '
                    </div>
                </div>
                ');
            }
        };
    }

    // <form method="post" class="thumbForm" action="/gallery.php?page=' . $currentPage . '">
    // <button type="submit" value="' . $picturesIDs[$i] . '" class="thumbUp" name="likeAPic" title="like"><img class="thumbPic" src="/filters/thumbDown.png" alt="" /></button>
    // </form>


    // <form method="post" action="/gallery.php?page=' . $currentPage . '">
    //     <p>
    //         <label>Commentaire</label>: <input type="text" maxlength="254" name="' . $picturesIDs[$i] . '" class="commentTextBar" required placeholder="Ex : Quelle belle photo !"/>
    //         <input type="submit" value="Envoyer" />
    //     </p>
    // </form>

    // <input type="submit" name="likeAPic" value="' . $picturesIDs[$i] . '" class="thumbUp" id="thumb' . $picturesIDs[$i] . '" alt="ThumbUp">

    // var_dump($auth->isSomeoneHere());

    // $IDs = $picture->getPicsID($db, $actualUserPseudo);
    // var_dump($picturesIDs);
    // var_dump($picture->getAuthor($db, 30));
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="/css/content.css" />
    </head>
    <body>
        <?php require_once 'elements/header.php'; ?>
            <div class="content">
                <div id="galleryZone">
                <?php
                    printPics($auth, $comments, $picture, $db, $picturesIDs, $commentsIDs, $first, $byPage, $currentPage, $likes, $actualUserPseudo);
                ?>
                    <!-- <div id="pic30" class="galleryBox"> -->
                        
                    <!-- </div>
                    <div id="pic30" class="galleryBox">
                        <img src="image.php?id=30" class="galleryPic" alt="">
                    </div>
                    <div id="pic30" class="galleryBox">
                        <img src="image.php?id=30" class="galleryPic" alt="">
                    </div>
                    <div id="pic30" class="galleryBox">
                        <img src="image.php?id=30" class="galleryPic" alt="">
                    </div> -->
                <!-- </div> -->
                </div>
                <nav>
                    <div id="paginationButtons">
                        <?php if ($currentPage != 1) {
                        echo ('<a href="/gallery.php/?page=' . ($currentPage - 1) . '" class="pageLink"><</a>');
                        }?>
                        <?php for($page = 1; $page <= $nbrPages; $page++) {
                            if($page == $currentPage) {
                                echo('<a href="/gallery.php/?page=' . $page . '" class="pageLinkActive">' . $page . '</a>');
                            }
                            else {
                                echo('<a href="/gallery.php/?page=' . $page . '" class="pageLink">' . $page . '</a>');
                            }
                        }; ?>
                        <?php if ($currentPage != $nbrPages) {
                            echo ('<a href="/gallery.php/?page=' . ($currentPage + 1) . '" class="pageLink">></a>');
                        }?>
                    </div>
                </nav>
            </div>
        <?php require_once 'elements/footer.php'?>
    </body>
</html>
