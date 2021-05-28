<?php
require_once "config/bootstrap.php";
$session = Session::getInstance();
$auth = App::getAuth();
$db = App::getDatabase();
$picture = new Pictures($session);

$actualUserPseudo = $auth->actualUser()->username;
// var_dump($actualUserPseudo);

$id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;

$userPic = $picture->getUserPic($db, $actualUserPseudo, $id);

foreach (getallheaders() as $name => $value) {
    header_remove($name);
};
ob_end_clean();

header('Content-Type: image/png');

echo $userPic;

error_reporting(E_ALL);
ini_set('display_errors', '1');