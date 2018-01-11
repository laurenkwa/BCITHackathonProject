<?php
session_start();

function __autoload($className){
    require_once("classes/$className.php");
} 

// redirect to home page if the user is not logged in
if (!isset($_SESSION['access_token'])) {
    // header("Location: ./../index.php");
}

// echo "<pre>";
// print_r($_POST);
// echo "</pre>";

// Open up a database using this file
$file = "./../xmls/offers.xml";
$database = new Database($file);
// increment the count attribute (total offers created including those deleted)
$database->getXML()->attributes()->count = $database->getXML()->attributes()->count + 1;
// add a new offer
$offer = $database->addNode("offer");
$offer->addAttribute("id", $database->getXML()->attributes()->count);
$offer->addChild("userid", "HARDCODED"); // $_SESSION['user_id']
$offer->addChild("username", "HARDCODED"); // $_SESSION['user_name']
$offer->addChild("date", $_POST['driver_date']);
$offer->addChild("time", $_POST['driver_time']);
$offer->addChild("start", $_POST['driver_start']);
$offer->addChild("end", $_POST['driver_end']);
$offer->addChild("seats", $_POST['driver_seats']);

// echo "<pre>";
// print_r($database->getXML());
// echo "</pre>";

// save the modification
$database->saveDatabase();

// redirection
// header("Location: ./../index.php");
?>