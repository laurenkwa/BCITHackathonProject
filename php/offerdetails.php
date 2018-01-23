<?php
session_start();

function __autoload($className){
    require_once("classes/$className.php");
} 

if (!isset($_GET['id'])) {
    echo "Location: " . $_SERVER['DOCUMENT_ROOT'] ."/php/error.php?code=2";
    header("Location: error.php?code=2");
    exit();
}
$offer_id = $_GET['id'];

$userDatabase = UserTable::getInstance();
$offerDatabase = OfferTable::getInstance();
$requestDatabase = RequestTable::getInstance();

$offer = $offerDatabase->getOffer($offer_id);
if ($offer == FALSE) {
    header("Location: error.php?code=3");
    exit();
} else {
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.html");
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/nav.php");

    echo "<div class=\"container\">";
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
    if (!isset($_SESSION['user_id']))
        echo "<p class=\"text-center bg-primary\">To reserve a seat, please sign in.</p>";
    else if ($_SESSION['user_id'] != $offer->getDriverID() && !$requestDatabase->ifUserReserved($offer->getID(), $_SESSION['user_id']) && $offer->getSeats() > 0)
        echo "<p><button class=\"btn btn-success\" onclick=\"setupModal(" . $offer->getID() .")\" data-toggle=\"modal\" data-target=\"#reserve_modal\">Reserve a seat</button></p>";
    else if ($_SESSION['user_id'] == $offer->getDriverID())
        echo "<p><a href=\"cancel_offer.php?id=" . $offer->getID() . "\"><button class=\"btn btn-danger\">Cancel Offer</button></a></p>";
    echo "</div>";
    echo "</div>";
    if ($_SESSION['user_id'] == $offer->getDriverID()) {
        
        $result = $requestDatabase->getRequestByOfferID($offer->getID());
        if (sizeof($result) != 0) {
        echo "<div class=\"row seg\">";
        echo "<div class=\"col-md-12\">";
        echo "<strong>Pending Requests: </strong>";
        echo "</div>";
        foreach ($result as $request) {
            $rider = $userDatabase->getUser($request->getRiderID());
            echo "<div class=\"col-md-9\">";
            echo "<strong>" . $rider->getName() . "</strong>: ";
            if (empty($msg))
                echo "No message left";
            else
                echo "Message: <strong> " . $msg . "</strong>";
            echo "</div>";
            $msg = $request->getMsg();
            
            echo "<div class\"col-md-3\">";   
            echo "<a class=\"btn btn-success\" type=\"button\" href=\"/php/accept_request.php?id=" . $request->getID() . "\">Accept Request</a>";
            echo "<a class=\"btn btn-danger\" type=\"button\" href=\"/php/cancel_request.php?id=" . $request->getID() . "\">Refuse Request</a>";
            
            echo "</div>";
        }
        echo "</div>";
        }

        
        $riders = $offer->getAllRider();
        if (sizeof($riders) != 0) {
        echo "<div class=\"row seg\">";
        echo "<div class=\"col-md-12\">";
        echo "<strong>Accepted Riders: </strong>";
        echo "</div>";
        foreach ($riders as $rider) {
            echo "<div class=\"col-md-10\">";
            echo "<strong>" . $rider->getName() . "</strong>";
            echo "</div>";
            echo "<div class=\"col-md-2\">";
            echo "<p><a href=\"remove_rider.php?offer_id=" . $offer->getID() . "&user_id=" . $rider->getID() . "\"><button class=\"btn btn-danger\">Remove</button></a></p>";
            echo "</div>";
        }
        echo "</div>";
        }
    } 
    $myRequests = $requestDatabase->getRequestByOfferIDAndRiderID($offer_id, $_SESSION['user_id']);
    if (sizeof($myRequests) != 0) {
        foreach ($myRequests as $request){
            echo "<div class=\"row seg\">";
            echo "<div class=\"col-md-12\">";
            echo "<h4>My request:</h4>";
            echo "</div>";
            echo "<div class=\"col-md-10\">";
            echo "<strong>Message</strong>: ";
            $msg = $request->getMsg();
            if (empty($msg))
                echo "No message left";
            else
                echo $msg;
            echo "</div>";
            echo "<div class=\"col-md-2\">";
            echo "<a class=\"btn btn-danger\" type=\"button\" href=\"/php/cancel_request.php?id=" . $request->getID() . "\">Cancel Request</a>";
            echo "</div>";
            echo "</div>";
        }
    }
    
        
    
}
?>
<div class="embed-responsive embed-responsive-16by9">
<iframe id="googleMap" class="embed-responsive-item" frameborder="0" style="border:0" allowfullscreen></iframe>
</div>
<script>
    $(function() {
        $("#googleMap").attr("src", "https://www.google.com/maps/embed/v1/directions?origin=" +
        <?php echo("\"" . $offer->getStartLocation() . "\""); ?> +
        "&destination=" +
        <?php echo("\"" . $offer->getDestination() . "\""); ?> +
        "&key=AIzaSyAh-wxnCsW7OZsqkWMHXLFtdjwLXo1PsqY");
    });
</script>
</div>

<?php 
include($_SERVER['DOCUMENT_ROOT'] . "/includes/reserve_modal.html");
include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.html"); 
?>