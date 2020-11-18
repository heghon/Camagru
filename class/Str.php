<?php
class Str {

    static function random($length) {
        $alpha = "0123456789azertyuiopqsdfghjklmwxcvbnAERTYUIOPQSDFGHJKLMWXCVBN";
        return substr(str_shuffle(str_repeat($alpha, $length)), 0, $length);
    }

}