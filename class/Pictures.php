<?php

class Pictures {

    private $session;

    public function __construct($session) {
        $this->session = $session;
    }

    public function uploadPicture($db, $username, $picture) {
        $date = date('Y-m-d');
        $name = $username . strval(time());
        $db->query("INSERT INTO pictures SET name = ?, picture = ?, author = ?, likes = ?, date = ?", [
            $name,
            $picture, 
            $username,
            0,
            $date]);
    }
}

?>