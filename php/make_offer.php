<?php
session_start();
echo "<pre>";
print_r($_POST);
echo "</pre>";
// redirect to home page if the user is not logged in
if (!isset($_SESSION['access_token'])) {
    // header("Location: index.php");
}
// Reads the xml file
$file = "./../xmls/offers.xml";
$xmlDoc = file_get_contents($file);
$xml = simplexml_load_string($xmlDoc) or die("Error: Cannot create object");

/**
 * remove a node with the given attribute and value.
 * 
 * @param target -> an iterable list of nodes
 * @param name -> the name of the attribute
 * @param value -> the value of the attribute
 * @return void
 */
function removeNode($target, $name, $value) {
    foreach($target as $item) {
        if($item[$name] == $value) {
            $dom=dom_import_simplexml($item);
            $dom->parentNode->removeChild($dom);
        }
    }
}

// increment count (this number indicates how many offers have been created, including those that is deleted/expired)
$xml->attributes()->count = $xml->attributes()->count + 1;
// add a new request in
$offer = $xml->addChild("offer");
$offer->addAttribute("id", $xml->attributes()->count);
$offer->addChild("userid", "HARDCODED"); // $_SESSION['user_id']
$offer->addChild("username", "HARDCODED"); // $_SESSION['user_name']
$offer->addChild("date", $_POST['driver_date']);
$offer->addChild("time", $_POST['driver_time']);
$offer->addChild("start", $_POST['driver_start']);
$offer->addChild("end", $_POST['driver_end']);
$offer->addChild("seats", $_POST['driver_seats']);

removeNode($xml->offer, "id", "5");

echo "<pre>";
print_r($xml);
echo "</pre>";
// Save to file
if ($xml->saveXML($file)) {
    echo "Completed successfully";
} else {
    echo "Error occurs when saving the offers.xml";
}
?>