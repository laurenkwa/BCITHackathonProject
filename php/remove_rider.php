<?php
session_start();

function __autoload($className){
    require_once("classes/$className.php");
} 

// redirect to home page if the user is not logged in
if (!isset($_SESSION['user_id']) || !isset($_GET['offer_id']) || !isset($_GET['user_id'])) {
    header("Location: error.php?code=1");
    exit();
}

// Open up a database using this file
$userDatabase = UserTable::getInstance();
$offerDatabase = OfferTable::getInstance();
$requestDatabase = RequestTable::getInstance();

// remove the offer
$offer = $offerDatabase->getOffer($_GET['offer_id']);
if ($offer == FALSE) {
    header("Location: error.php?code=2");
    exit();
}
if ($offer->getDriverID() != $_SESSION['user_id']) {
    header("Location: error.php?code=2");
    exit();
}

$offer->removeRider($_GET['user_id']);
$rider = $userDatabase->getUser($_GET['user_id']);
$rider->addNotification("You are removed from a ride",
"You are removed for the ride<br> From <strong>" . $offer->getStartLocation() . "</strong> to <strong>" . $offer->getDestination() . 
"</strong> by the driver <a href=\"/php/offerdetails.php?id=" . $offer->getID() . "\">offer #" . $offer->getID() . "</a>");

// save the modification
$offerDatabase->save();
$requestDatabase->save();
$userDatabase->save();

// redirection
header("Location: ./offerdetails.php?id=" . $offer->getID());

?>