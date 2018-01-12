<?php
session_start();

function __autoload($className){
    require_once("classes/$className.php");
} 

// redirect to home page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ./error.php?code=1");
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

$offer = $offerDatabase->searchNode("offer", "id", $_POST['id']);
// load both the driver's and the rider's user data
$driver = $userDatabase->searchNode("user", "id", $offer->userid->__toString());
$rider = $userDatabase->searchNode("user", "id", $_SESSION['user_id']);
if ($driver == FALSE || $rider == FALSE) {
    header("Location: ./error.php?code=4");
    exit();
}

// append the request to rider's user data
$dt = new DateTime();
// $request = $rider->requestlist->addChild("request");
// increment the count attribute (total offers created including those deleted)
$requestDatabase->getXML()->attributes()->count = $requestDatabase->getXML()->attributes()->count + 1;
$requestID = $requestDatabase->getXML()->attributes()->count;
// add a new request
$request = $requestDatabase->addNode("request");
$request->addAttribute("id", $requestID);
$request->addChild("offer_id", $_POST['id']);
$request->addChild("driver_id", $driver->attributes()->id);
$request->addChild("rider_id", $rider->attributes()->id);
$request->addChild("request_time", $dt->format("Y-m-d H:i:s"));
$request->addChild("msg", $_POST['msg']);

// add to driver's user data
$driver->receivedlist->addChild("received", $requestID);
$driver->notification->addChild("msg", "You have received an request from " . $rider->attributes()->name . " for the driver offer id: " . $request->offer_id);
// add to rider's user data
$rider->requestlist->addChild("request", $requestID);

// echo "<pre>";
// print_r($userDatabase->getXML());
// echo "</pre>";

// save the modification
$userDatabase->saveDatabase();
$requestDatabase->saveDatabase();

// redirection
header("Location: ./../index.php");

?>