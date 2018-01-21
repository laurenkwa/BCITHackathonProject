<?php
    function __autoload($className){
        require_once("php/classes/$className.php");
    } 
    
    $offerDatabase = OfferTable::getInstance();
    $requestDatabase = RequestTable::getInstance();
    
    if ($offerDatabase->size() == 0) {
        echo "<div class='container'><section><p>No offer has been posted!</p></section></div>";
    } else {
        echo "<div class=\"row\"><div class=\"col-md-12 text-center bg-primary\">" . $offerDatabase->size() . " offer(s) available</div></div>";
        foreach ($offerDatabase->getAllOffer() as $offer) {
            echo "<div class=\"row seg\">";
            echo "<div class=\"col-md-5\">";
            echo "<p><strong>Driver: </strong>" . $offer->getDriverName() ."</p>";
            echo "<p><strong>Depart From: </strong>" . $offer->getStartLocation() ."</p>";
            echo "<p><strong>Seats Available: </strong>" . $offer->getSeats() ."</p>";
            echo "</div>";
            echo "<div class=\"col-md-5\">";
            echo "<p><strong>Time: </strong>" . $offer->getTime() ." " . $offer->getDate() ."</p>";
            echo "<p><strong>Destination: </strong>" . $offer->getDestination() ."</p>";
            echo "</div>";
            echo "<div class=\"col-md-2\">";
            echo "<p><a href=\"php/offerdetails.php?id=" . $offer->getID() . "\"><button class=\"btn btn-primary\">More details</button></a></p>";
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $offer->getDriverID() && !$requestDatabase->ifUserReserved($offer->getID(), $_SESSION['user_id']))
                echo "<p><button class=\"btn btn-success\" onclick=\"setupModal(" . $offer->getID() .")\" data-toggle=\"modal\" data-target=\"#reserve_modal\">Reserve a seat</button></p>";
            echo "</div>";
            if ($offer->getSeats() < 1)
                echo "<div class=\"col-md-12 text-center bg-primary\">No available seats for this offer</div>";
            echo "</div>";
            
        }
    }
?>