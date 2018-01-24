<?php
session_start();

function __autoload($className){
    require_once("classes/$className.php");
} 

// redirect to home page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: error.php?code=1");
    exit();
}

// Open up a database using this file
$userDatabase = UserTable::getInstance();
$offerDatabase = OfferTable::getInstance();
$requestDatabase = RequestTable::getInstance();

if ($requestDatabase->ifUserReserved($_POST['id'], $_SESSION['user_id'])) {
    header("Location: error.php?code=6");
    exit();
}

$offer = $offerDatabase->getOffer($_POST['id']);
if ($offer->getSeats() < 1) {
    header("Location: error.php?code=2");
    exit();
}
// load both the driver's and the rider's user data
$driver = $userDatabase->getUser($offer->getDriverID());
$rider = $userDatabase->getUser($_SESSION['user_id']);
if ($driver->getID() == $_SESSION['user_id']) {
    header("Location: error.php?code=7");
    exit();
}
if ($driver == FALSE || $rider == FALSE) {
    header("Location: error.php?code=4");
    exit();
}

// append the request to rider's user data
$dt = new DateTime();
$info = array(
    "offer_id"      => $_POST['id'],
    "driver_id"     => $driver->getID(),
    "rider_id"      => $rider->getID(),
    "msg"           => $_POST['msg']
);
$request = $requestDatabase->addRequest($info);

$driver->addNotification("You have received a request",
"<strong>" . $rider->getName() . 
"</strong> requested a seat for the offer<br> From <strong>" . $offer->getStartLocation() . "</strong> to <strong>" . $offer->getDestination() . 
"</strong> <a href=\"/php/offerdetails.php?id=" . $_POST['id'] . "\">offer #" . $_POST['id'] . "</a>");

$driver->addReceived($request->getID());
$rider->addRequest($request->getID());

// save the modification
$userDatabase->save();
$requestDatabase->save();
$offerDatabase->save();

// redirection
header("Location: ./offerdetails.php?id=" . $_POST['id']);

?>