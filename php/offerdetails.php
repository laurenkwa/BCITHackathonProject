<?php
session_start();

function __autoload($className){
    require_once("classes/$className.php");
} 

function isAlreadyReserved($id) {
    $file = "./../xmls/requests.xml";
    $database = new Database($file);
    $result = $database->searchNodes("/list/request", NULL, array("offer_id" => $id));
    foreach ($result as $node) {
        if ($node->rider_id->__toString() == $_SESSION['user_id']) {
            return true;
        }
    }
    return false;
}

if (!isset($_GET['id'])) {
    header("Location: error.php?code=2");
}
$offer_id = $_GET['id'];
$file = "./../xmls/offers.xml";
$database = new Database($file);

$offer = $database->searchNodes("/list/offer", NULL, array("id" => $offer_id))[0];
if ($offer == FALSE) {
    header("Location: error.php?code=3");
    exit();
} else {
    include("./../includes/header.html");
    include("./../includes/nav.php");

    echo "<div class=\"container\">";
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
    if (!isset($_SESSION['user_id']))
        echo "<p class=\"text-center bg-primary\">To reserve a seat, please sign in.</p>";
    else if ($_SESSION['user_id'] != $offer->userid && !isAlreadyReserved($offer->attributes()->id) && $offer->seats > 0)
        echo "<p><button class=\"btn btn-success\" onclick=\"setupModal(" . $offer->attributes()->id .")\" data-toggle=\"modal\" data-target=\"#reserve_modal\">Reserve a seat</button></p>";
    else if ($_SESSION['user_id'] == $offer->userid)
        echo "<p><a href=\"cancel_offer.php?id=" . $offer->attributes()->id . "\"><button class=\"btn btn-danger\">Cancel Offer</button></a></p>";
    echo "</div>";
    echo "</div>";
}
?>
<div class="embed-responsive embed-responsive-16by9">
<iframe id="googleMap" class="embed-responsive-item" frameborder="0" style="border:0" allowfullscreen></iframe>
</div>
<script>
    $(function() {
        $("#googleMap").attr("src", "https://www.google.com/maps/embed/v1/directions?origin=" +
        <?php echo("\"" . $offer->start . "\""); ?> +
        "&destination=" +
        <?php echo("\"" . $offer->end . "\""); ?> +
        "&key=AIzaSyAh-wxnCsW7OZsqkWMHXLFtdjwLXo1PsqY");
    });
</script>
</div>

<?php 
include("./../includes/reserve_modal.html");
include("./../includes/footer.html"); 
?>