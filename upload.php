<?php
require_once "config/bootstrap.php";
$session = Session::getInstance();
$auth = App::getAuth();
$db = App::getDatabase();
$picture = new Pictures($session);

// (A) SET THE DESTINATION FOLDER
$imageName = $_FILES["upimage"]["tmp_name"];

$image_tmp = (file_get_contents($_FILES["upimage"]["tmp_name"]));

$picture->uploadPicture($db, $auth->actualUser()->username, $image_tmp);
// $destination = "uploaded.png";

// (B) MOVE UPLOADED FILE TO DESTINATION
// echo move_uploaded_file($source, $destination) ? "OK" : "ERROR UPLOADING";