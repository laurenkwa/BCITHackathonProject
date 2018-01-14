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
$userFile = "./../xmls/users.xml";
$userDatabase = new Database($userFile);

$offerFile = "./../xmls/offers.xml";
$offerDatabase = new Database($offerFile);

$requestFile = "./../xmls/requests.xml";
$requestDatabase = new Database($requestFile);

// remove the offer
$offer = $offerDatabase->searchNode("offer", "id", $_GET['id']);
if ($offer == FALSE) {
    header("Location: ./error.php?code=2");
    exit();
} else if ($offer->userid == $_SESSION['user_id']) {
    $offerDatabase->removeNode("offer", "id", $_GET['id']);
}

while ($request = $requestDatabase->searchNode2("request", "offer_id", $_GET['id'])) {
    $requestDatabase->removeChild($request); 
    $node = $userDatabase->searchNodeByChild($userDatabase->getXML()->user, "requestlist", "request", $_GET['id'], FALSE);
    $userDatabase->removeChild($node);
}

// echo "<pre>";
// print_r($offerDatabase->getXML());
// echo "</pre>";

// save the modification
$offerDatabase->saveDatabase();
$requestDatabase->saveDatabase();
$userDatabase->saveDatabase();

// redirection
header("Location: ./../index.php");

?>