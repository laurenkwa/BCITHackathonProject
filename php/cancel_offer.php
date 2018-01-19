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

// Open up a database using this file
$userDatabase = UserTable::getInstance();

$offerDatabase = OfferTable::getInstance();

$requestFile = "/xmls/requests.xml";
$requestDatabase = Database::openFromFile($requestFile);

// remove the offer
$offer = $offerDatabase->getOffer($_GET['id']);
if ($offer == FALSE) {
    header("Location: ./error.php?code=2");
    exit();
} else if ($offer->getDriverID() == $_SESSION['user_id']) {
    $offer->remove();
}

foreach ($requestDatabase->searchNodes("/list/request", NULL, array("offer_id" => $_GET['id'])) as $request) {
    $requestDatabase::removeChild($request); 
    $userDatabase->removeAllRequest($_GET['id']);
    $userDatabase->removeAllReceived($_GET['id']);
}


// save the modification
$offerDatabase->save();
$requestDatabase->saveDatabase();
$userDatabase->save();

// redirection
header("Location: ./../index.php");

?>