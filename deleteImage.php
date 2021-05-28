<?php
require_once "config/bootstrap.php";
$session = Session::getInstance();
$db = App::getDatabase();
$picture = new Pictures($session);

$id =  $_GET["id"];

$picture->deleteImage($db, $id);
