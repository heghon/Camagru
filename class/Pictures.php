<?php

class Pictures {

    private $session;

    public function __construct($session) {
        $this->session = $session;
    }

    public function uploadPicture($db, $username, $picture) {
        $date = date('Y-m-d');
        $name = $username . strval(time());
        $db->query("INSERT INTO pictures SET name = ?, mime = ?, picture = ?, author = ?, likes = ?, date = ?", [
            $name,
            "image/png",
            $picture, 
            $username,
            0,
            $date]);
    }

    public function getUserPic($db, $username, $id) {
        $pic = $db->fetcher("SELECT picture FROM pictures WHERE author = ? AND id = ?", [
            $username, 
            $id
        ]);
        return $pic;
    }

    public function getUserPicsID($db, $username) {
        $ids = $db->query("SELECT id FROM pictures WHERE author = ?", [
            $username
        ]);
        return ($ids->fetchAll(PDO::FETCH_COLUMN, 0));
    }

    public function deleteImage($db, $id) {
        $db->query("DELETE FROM pictures WHERE pictures . id = ?", [
            $id
        ]);
    }
}

?>