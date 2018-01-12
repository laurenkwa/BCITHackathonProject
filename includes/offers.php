<?php
    function __autoload($className){
        require_once("php/classes/$className.php");
    } 
    
    $file = "./xmls/offers.xml";
    $database = new Database($file);
    if ($database->size() == 0) {
        echo "<div class=\"row seg\"><div class=\"col-md-12\">No offer has been posted</div></div>";
    } else {
        foreach ($database->getXML()->offer as $offer) {
            echo "<div class=\"row seg\">";
            echo "<div class=\"col-md-5\">";
            echo "<p><strong>Driver: </strong>" . $offer->username ."</p>";
            echo "<p><strong>Depart From: </strong>" . $offer->start ."</p>";
            echo "<p><strong>Seats Available: </strong>" . $offer->seats ."</p>";
            echo "</div>";
            echo "<div class=\"col-md-5\">";
            echo "<p><strong>Time: </strong>" . $offer->time ." " . $offer->date ."</p>";
            echo "<p><strong>Destination: </strong>" . $offer->end ."</p>";
            echo "</div>";
            echo "<div class=\"col-md-2\">";
            echo "<p><a href=\"php/offerdetails.php?id=" . $offer->attributes()->id . "\"><button class=\"btn btn-default\">More details</button></a></p>";
            echo "<p><button class=\"btn btn-success\" onclick=\"setupModal(" . $offer->attributes()->id .")\" data-toggle=\"modal\" data-target=\"#reserve_modal\">Reserve a seat</button></p>";
            echo "</div>";
            echo "</div>";
        }
    }
?>