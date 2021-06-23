<?php

class Comments {

    private $session;

    public function __construct($session) {
        $this->session = $session;
    }

    public function uploadComment($db, $pictureID, $pictureAuthorName, $email , $authorCommentAccord , $commentAuthorName, $comment) {
        $date = date('Y-m-d');
        $db->query("INSERT INTO comments SET pictureID = ?, author = ?, comment = ?, date = ?", [
            $pictureID,
            $commentAuthorName,
            $comment, 
            $date]);
        if ($authorCommentAccord) {
            mail($email, "Am'Stram'Gram - Vous avez un nouveau commentaire", "Bonjour $pictureAuthorName, quelqu'un vous a envoyé un commentaire sous l'une de vos photos.\r\nN'hésitez pas à nous rendre visite pour lui répondre ou prendre d'autre photos - \r\nhttp://localhost/index.php", "From: Am'Stram'Gram");
        }
    }

    public function getCommentsIDs($db) {
        $ids = $db->query("SELECT id FROM comments");
        return ($ids->fetchAll(PDO::FETCH_COLUMN, 0));
    }

    public function deleteComment($db, $id) {
        $db->query("DELETE FROM comments WHERE comments . id = ?", [
            $id
        ]);
    }

    public function getAuthor($db, $id) {
        $auth = $db->query("SELECT author FROM comments WHERE id = ?", [
            $id
        ]);
        return ($auth->fetch(PDO::FETCH_COLUMN, 0));
    }

    public function getComment($db, $id) {
        $com = $db->query("SELECT comment FROM comments WHERE id = ?", [
            $id
        ]);
        return ($com->fetch(PDO::FETCH_COLUMN, 0));
    }

    public function getPictureID($db, $id) {
        $picID = $db->query("SELECT pictureID FROM comments WHERE id = ?", [
            $id
        ]);
        return ($picID->fetch(PDO::FETCH_COLUMN, 0));
    }
}

?>