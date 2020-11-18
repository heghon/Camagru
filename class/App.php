<?php
class App {

    static $db = null;

    static function getDatabase() {
        if (!self::$db) {
            self::$db = new Database();
        }
        return self::$db;
    }

    static function getAuth() {
        return new Auth(Session::getInstance(),
            ["restriction_msg" => "Vous n'avez pas le droit d'accéder à cette page."]);
    }

    static function redirect($file) {
        header("location: $file");
        exit();
    }

}