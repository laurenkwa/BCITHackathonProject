<?php
session_start();

function __autoload($className){
    require_once("classes/$className.php");
} 

function isAlreadyReserved($id) {
    $file = "/xmls/requests.xml";
    $database = Database::openFromFile($file);
    $result = $database->searchNodes("/list/request", NULL, array("offer_id" => $id));
    foreach ($result as $node) {
        if ($node->rider_id->__toString() == $_SESSION['user_id']) {
            return true;
        }
    }
    return false;
}

// redirect to home page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ./error.php?code=1");
    exit();
}

if (isAlreadyReserved($_POST['id'])) {
    header("Location: ./error.php?code=6");
    exit();
}

// Open up a database using this file
$userDatabase = UserTable::getInstance();

$offerFile = "/xmls/offers.xml";
$offerDatabase = Database::openFromFile($offerFile);

$requestFile = "/xmls/requests.xml";
$requestDatabase = Database::openFromFile($requestFile);

$offer = $offerDatabase->searchNodes("/list/offer", NULL, array("id" => $_POST['id']))[0];
if ($offer->seats < 1) {
    header("Location: ./error.php?code=2");
    exit();
}
// load both the driver's and the rider's user data
$driver = $userDatabase->getUser($offer->userid->__toString());
$rider = $userDatabase->getUser($_SESSION['user_id']);
if ($driver->getID() == $_SESSION['user_id']) {
    header("Location: ./error.php?code=7");
    exit();
}
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
$request->addAttribute("offer_id", $_POST['id']);
$request->addChild("driver_id", $driver->getID());
$request->addChild("rider_id", $rider->getID());
$request->addChild("request_time", $dt->format("Y-m-d H:i:s"));
$request->addChild("msg", $_POST['msg']);


// add to driver's user data
$driver->addReceived($requestID);
$driver->addNotification("You have received a request",
"<strong>" . $rider->getName() . 
"</strong> request a seat for the offer<br> From <strong>" . $offer->start->__toString() . "</strong> to <strong>" . $offer->end->__toString() . 
"</strong> <a href=\"/php/offerdetails.php?id=" . $_POST['id'] . "\">offer #" . $_POST['id'] . "</a>");
// add to rider's user data
$rider->addRequest($requestID);

// save the modification
$userDatabase->save();
$requestDatabase->saveDatabase();
$offerDatabase->saveDatabase();

// redirection
header("Location: ./../index.php");

?>