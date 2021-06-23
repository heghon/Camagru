<?php
require_once "config/bootstrap.php";
$session = Session::getInstance();
$db = App::getDatabase();
$picture = new Pictures($session);

$id =  htmlentities($_GET["id"], ENT_QUOTES);

$picture->deleteImage($db, $id);
