<?php
session_start();

function __autoload($className){
    require_once("classes/$className.php");
} 

// redirect to home page if the user is not logged in
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: ./../php/error.php?code=1");
    exit();
}
// echo "<pre>";
// print_r($_POST);
// echo "</pre>";

// Open up a database using this file
$userDatabase = UserTable::getInstance();

$offerFile = "/xmls/offers.xml";
$offerDatabase = Database::openFromFile($offerFile);

$requestFile = "/xmls/requests.xml";
$requestDatabase = Database::openFromFile($requestFile);

// remove the offer
$offer = $offerDatabase->searchNodes("/list/offer", NULL, array("id" => $_GET['id']))[0];
if ($offer == FALSE) {
    header("Location: ./error.php?code=2");
    exit();
} else if ($offer->userid == $_SESSION['user_id']) {
    $offerDatabase->removeNodes("/list/offer", NULL, array("id" => $_GET['id']))[0];
}

foreach ($requestDatabase->searchNodes("/list/request", NULL, array("offer_id" => $_GET['id'])) as $request) {
    $requestDatabase::removeChild($request); 
    $userDatabase->removeAllRequest($_GET['id']);
    $userDatabase->removeAllReceived($_GET['id']);
}

// echo "<pre>";
// print_r($offerDatabase->getXML());
// echo "</pre>";

// save the modification
$offerDatabase->saveDatabase();
$requestDatabase->saveDatabase();
$userDatabase->save();

// redirection
header("Location: ./../index.php");

?>