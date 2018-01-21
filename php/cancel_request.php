<?php
session_start();

function __autoload($className){
    require_once("classes/$className.php");
} 

// redirect to home page if the user is not logged in
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: " . $_SERVER['DOCUMENT_ROOT'] ."/php/error.php?code=1");
    exit();
}

// Open up a database using this file
$userDatabase = UserTable::getInstance();
$offerDatabase = OfferTable::getInstance();
$requestDatabase = RequestTable::getInstance();

// remove the offer
$request = $requestDatabase->getRequestByID($_GET['id']);
if ($request == FALSE) {
    header("Location: " . $_SERVER['DOCUMENT_ROOT'] ."/php/error.php?code=2");
    exit();
}
$isDriver = $request->getDriverID() == $_SESSION['user_id'];
$isRider = $request->getRiderID() == $_SESSION['user_id'];
if (!$isDriver && !$isRider) {
    header("Location: " . $_SERVER['DOCUMENT_ROOT'] ."/php/error.php?code=2");
    exit();
}

$offer = $offerDatabase->getOffer($request->getOfferID());

if ($isDriver) {
    $rider = $userDatabase->getUser($request->getRiderID());
    $rider->addNotification("You request is accepted",
    "Your request for the offer<br> From <strong>" . $offer->getStartLocation() . "</strong> to <strong>" . $offer->getDestination() . 
    "</strong> is refused <a href=\"/php/offerdetails.php?id=" . $offer->getID() . "\">offer #" . $offer->getID() . "</a>");
} else if ($isRider) {
    $driver = $userDatabase->getUser($request->getDriverID());
    // 
}

$request->remove();
$userDatabase->removeAllRequest($_GET['id']);
$userDatabase->removeAllReceived($_GET['id']);

// save the modification
$requestDatabase->save();
$userDatabase->save();

// redirection
header("Location: ./../index.php");

?>