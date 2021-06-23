<?php
require_once "config/bootstrap.php";
$session = Session::getInstance();
$auth = App::getAuth();
$db = App::getDatabase();
$pictures = new Pictures($session);
$likes = new Likes($session);

// (A) SET THE DESTINATION FOLDER
$imageName = $_FILES["upimage"]["tmp_name"];

$image_tmp = (file_get_contents($_FILES["upimage"]["tmp_name"]));

$pictures->uploadPicture($db, $auth->actualUser()->username, $image_tmp);

$likes->uploadLikeSystem($db, $db->lastInsertedId(), $auth->getUserID($db, $auth->actualUser()->username));

// $destination = "uploaded.png";

// (B) MOVE UPLOADED FILE TO DESTINATION
// echo move_uploaded_file($source, $destination) ? "OK" : "ERROR UPLOADING";