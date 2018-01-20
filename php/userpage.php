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

    // Open up a database using this file
    $userDatabase = UserTable::getInstance();
    $offerDatabase = OfferTable::getInstance();
    $requestDatabase = RequestTable::getInstance();

    $user = $userDatabase->getUser($_SESSION['user_id']);
    if (empty($user)) {
        header("Location: ./error.php?code=4");
    }

    include("./../includes/header.html");
    include("./../includes/nav.php");
    ?>
    <div class="container">
    <div class="panel-group" id="accordion">
        <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">
            <a data-toggle="collapse" href="#notification">Notification</a>
            </h4>
        </div>
        <div id="notification" class="panel-collapse collapse in">
            <div class="panel-body">
            <?php
            $msgs = $user->getAllNotification();
            if (sizeof($msgs) == 0) {
                echo "<div class=\"row seg text-center\">";
                echo "No notifications";
                echo "</div>";
            } else {
                foreach ($msgs as $msg) {
                    echo "<div class=\"row seg\">";
                    echo "<div class=\"row\">";
                    echo "<div class=\"col-md-6\" style=\"font-weight: bold;\">";
                    echo $msg->attributes()->title;                
                    echo "</div>";
                    echo "<div class=\"col-md-6 text-right\">";
                    echo $msg->attributes()->time;                
                    echo "</div>";
                    echo "</div>";
                    echo "<div class=\"row\">";
                    echo "<div class=\"col-md-12\" style=\"font-size: small; padding-left: 40px;\">";
                    echo $msg->__toString();
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            ?>
        </div>
        </div>
        <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">
            <a data-toggle="collapse" href="#offers">My Offers</a>
            </h4>
        </div>
        <div id="offers" class="panel-collapse collapse collapse in">
            <div class="panel-body">
            <?php
            $result = $offerDatabase->getOfferByDriverID($_SESSION['user_id']);
            
            if (sizeof($result) == 0) {
                echo "<div class=\"row seg text-center\">";
                echo "No Offers";
                echo "</div>";
            } else {
                foreach ($result as $offer){
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
                    echo "<p><a href=\"/php/offerdetails.php?id=" . $offer->getID() . "\"><button class=\"btn btn-primary\">More details</button></a></p>";
                    echo "<p><a href=\"/php/cancel_offer.php?id=" . $offer->getID() . "\"><button class=\"btn btn-danger\">Cancel Offer</button></a></p>";
                    echo "</div>";
                }
            }
            ?>
            </div>
        </div>
        </div>
        <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">
            <a data-toggle="collapse" href="#requests">My Requests</a>
            </h4>
        </div>
        <div id="requests" class="panel-collapse collapse collapse in">
            <div class="panel-body">
            <?php
            $result = $requestDatabase->getRequestByRider($_SESSION['user_id']);

            if (sizeof($result) == 0) {
                echo "<div class=\"row seg text-center\">";
                echo "No Request";
                echo "</div>";
            } else {
                foreach ($result as $request){
                    // echo "<pre>";
                    // var_dump($request);
                    // echo "</pre>";
                    $offer = $offerDatabase->getOffer($request->getOfferID());
                    echo "<div class=\"row seg\">";
                    echo "<div class=\"row\">";
                    echo "<div class=\"col-md-10\">";
                    echo "From <strong>" . $offer->getStartLocation() . " </strong>to<strong> " . $offer->getDestination() . "</strong>
                    | Driver: <strong>" . $offer->getDriverName() . "</strong>";
                    echo "</div>";
                    echo "<div class=\"col-md-2\">";
                    echo "<a href=\"/php/offerdetails.php?id=" . $offer->getID() . "\"><button class=\"btn btn-primary\">More details</button></a>";
                    echo "</div>";
                    echo "</div>";
                    echo "<div class=\"row\">";
                    echo "<div class=\"col-md-10\" style=\"font-size: small; padding-left: 40px;\">";
                    $msg = $request->getMsg();
                    if (empty($msg))
                        echo "No message left";
                    else
                        echo "Message: " . $msg;
                    echo "</div>";
                    echo "<div class=\"col-md-2\">";                    
                    echo "<p><a href=\"/php/cancel_request.php?id=" . $request->getID() . "\"><button class=\"btn btn-danger\">Cancel Request</button></a></p>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            ?>
            </div>
        </div>
        </div>
        <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">
            <a data-toggle="collapse" href="#received">Received Requests</a>
            </h4>
        </div>
        <div id="received" class="panel-collapse collapse collapse in">
            <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
        </div>
        </div>
    </div> 
    </div>

    <?php
    include("./../includes/footer.html"); 
?>