<?php
session_start();

function __autoload($className){
    require_once("classes/$className.php");
} 

// redirect to home page if the user is not logged in
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: error.php?code=1");
    exit();
}

// Open up a database using this file
$userDatabase = UserTable::getInstance();
$offerDatabase = OfferTable::getInstance();
$requestDatabase = RequestTable::getInstance();

// remove the offer
$offer = $offerDatabase->getOffer($_GET['id']);
if ($offer == FALSE) {
    header("Location: error.php?code=2");
    exit();
}
if ($offer->getDriverID() != $_SESSION['user_id']) {
    header("Location: error.php?code=2");
    exit();
}

$offer->remove();

foreach ($requestDatabase->getRequestByOfferID($_GET['id']) as $request) {
    $request->remove();
    $userDatabase->removeAllRequest($request->getID());
    $userDatabase->removeAllReceived($request->getID());
}


// save the modification
$offerDatabase->save();
$requestDatabase->save();
$userDatabase->save();

// redirection
header("Location: ./userpage.php");

?>