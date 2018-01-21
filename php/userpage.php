<?php
    session_start();
    

    function __autoload($className){
        require_once("classes/$className.php");
    } 

    // redirect to home page if the user is not logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: " . $_SERVER['DOCUMENT_ROOT'] ."/php/error.php?code=1");
        exit();
    }

    // Open up a database using this file
    $userDatabase = UserTable::getInstance();
    $offerDatabase = OfferTable::getInstance();
    $requestDatabase = RequestTable::getInstance();

    $user = $userDatabase->getUser($_SESSION['user_id']);
    if (empty($user)) {
        header("Location: " . $_SERVER['DOCUMENT_ROOT'] ."/php/error.php?code=4");
    }

    include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.html");
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/nav.php");
    ?>

<section id="one" class="wrapper style1 special">
                <div class="container" id="inbox">
                    <header class="major">
                        <h2>Welcome to your Inbox!</h2>
                        <br/><br/>
                        <a href="/index.php"><button class="button alt">Back to Home</button></a>
                        
                    </header>
                    <div class="row 150%">
                        <div class="4u 12u$(medium)">
                            <section class="box">
                                <a href="userpage.php#notifications">
                                <img src="https://image.flaticon.com/icons/svg/321/321817.svg" height="100" width="100" />
                                <br/><br/>
                                <h1>Notifications</h1>
                                </a>
                                <br/><br/>
                                <p>Check out what's new!</p>
                            </section>
                        </div>
                        <div class="4u 12u$(medium)">
                            <section class="box">
                                <a href="userpage.php#requests">
                                <img src="https://image.flaticon.com/icons/svg/129/129517.svg" height="98" width="95" />
                                <br/><br/>
                                <h1>My Requests</h1>
                                </a>
                                <br/><br/>
                                <p>View the status of your requests</p>
                            </section>
                        </div>
                        <div class="4u$ 12u$(medium)">
                            <section class="box">
                                <a href="userpage.php#offers">
                                <img src="https://image.flaticon.com/icons/svg/254/254048.svg" height=100 width="95" />
                                <br/><br/>
                                <h1>Offers</h1>
                                </a>
                                <br/><br/>
                                <p>View the status of your offers</p>
                            </section>
                        </div>
                    </div>
                </div>
            </section>

             <!-- View Offers -->
            <section id="two" class="wrapper style2">
                <div class="container" id="notifications">
                        <header class="major">
                            <h2>View Notifications</h2>
                            <img src="https://image.flaticon.com/icons/svg/321/321817.svg" height=200 width=200/>
                            <br/><br/>
                            <a href="#"><button class="button alt">Back to Inbox</button></a>
                        </header>
                        <section>
                            <div class="row">
                                <section class="container">
                                <?php
            $msgs = $user->getAllNotification();
            if (sizeof($msgs) == 0) {
                echo "<div class=\"row seg text-center\">";
                echo "No notifications";
                echo "</div>";
            } else {
                foreach ($msgs as $msg) {
                    echo "<header class='major'>";
                    echo "<div class='seg'>";

                    //echo "<div class=\"row\">";
                    //echo "<div class=\"col-md-6\" style=\"font-weight: bold;\">";
                    echo "<h3>" . $msg->attributes()->title . "</h3>";                
                    //echo "</div>";
                    //echo "<div class=\"col-md-6 text-right\">";
                    echo "<br/>";
                    echo "<p>" . $msg->attributes()->time . "</p>";                
                    //echo "</div>";
                    //echo "</div>";
                    //echo "<div class=\"row\">";
                    //echo "<div class=\"col-md-12\" style=\"font-size: small; padding-left: 40px;\">";
                    echo "<p>" . $msg->__toString() . "</p>";
                    echo "</div>";
                    //echo "</div>";
                    //echo "</div>";
                    echo "</header>";
                }
            }
            ?>
                                         
                                </section>
                            </div>
                        </section>
                </div>
               
            </section>
            <!-- Offers -->
            <section id="two" class="wrapper style3">
                <div class="container" id="offers">
                        <header class="major">
                            <h2>My Offers</h2>

                            <p>View pending requests from passengers</p>

                            <img src="https://image.flaticon.com/icons/svg/254/254048.svg" height=200 width=200/>
                            <br/><br/>
                            <a href="#"><button class="btn btn default">Back to Inbox</button></a>
                        </header>
                        <div class="container">
                            <section class='seg'>
                            
                                <?php
                                    $result = $offerDatabase->getOfferByDriverID($_SESSION['user_id']);
            
                                if (sizeof($result) == 0) {
                                    
                                    echo "<header class='major'>";
                                    echo "<h3>No Offers</h3>";
                                    echo "</header></section>";
                                } else {
                                    foreach ($result as $offer){
                                        
                                        echo "<div class='row 150%'>";
                                        echo "<h3>Offer:</h3>";
                                        echo "</div>";
                                        echo "<header class='major'>";
                                        echo "<div class='row 150%'>";
                                        echo "<div class='6u 12u$(4)'>";
                                        echo "<p><strong>Driver: </strong>" . $offer->getDriverName() . "</p>";
                                        echo "<p><strong>Depart From: </strong>" . $offer->getStartLocation() ."</p>";
                                        echo "<p><strong>Seats Available: </strong>" . $offer->getSeats() ."</p>";
                                        echo "</div>";
                                        echo "<div class=\"col-md-5\">";
                                        echo "<p><strong>Time: </strong>" . $offer->getTime() ." " . $offer->getDate() ."</p>";
                                        echo "<p><strong>Destination: </strong>" . $offer->getDestination() ."</p>";
                                        //echo "</div>";
                                        //echo "<div class=\"col-md-2\">";
                                        echo "</div></div>";
                                        echo "<p><a class=\"btn btn-primary\" type=\"button\" href=\"/php/offerdetails.php?id=" . $offer->getID() . "\">More details</a></p>";
                                        echo "<p><a class=\"btn btn-danger\" type=\"button\" href=\"/php/cancel_offer.php?id=" . $offer->getID() . "\">Cancel Offer</a></p>";
                                        echo "</header>";

                                        echo "<hr/>";

                                        // received requests for offer
                               
                                        $result = $requestDatabase->getRequestByDriver($_SESSION['user_id']);

                                        if (sizeof($result) == 0) {
                                            echo "<header class='major'>";
                                            echo "<h2>No Requests</h2>";
                                            echo "</header>";
                                        } else {
                                            foreach ($result as $request){
                                                $offer = $offerDatabase->getOffer($request->getOfferID());
                                                $rider = $userDatabase->getUser($request->getRiderID());
                                                //echo "<div class='row 150%'>";
                                                //echo "<div class=\"row\">";
                                                //echo "<div class=\"col-md-10\">";

                                                echo "<br/><h3>Pending Passenger Requests:</h3>";
                                                echo "<header class='major'>";
                                                echo "<br/>Rider: <strong>" . $rider->getName() . "</strong><br/>";

                                                //echo "</div>";
                                                //echo "<div class=\"col-md-2\">";
                                                //echo "<p><a class=\"btn btn-primary\" type=\"button\" href=\"/php/offerdetails.php?id=" . $offer->getID() . "\">More details</a></p>";
                                                //echo "</div>";
                                                //echo "</div>";
                                                //echo "<div class='6u 12u$(4)'><br/>";
                                                //echo "<div class=\"col-md-10\" style=\"font-size: small; padding-left: 40px;\">";

                                                
                                                $msg = $request->getMsg();
                                                if (empty($msg))
                                                    echo "No message left";
                                                else
                                                    echo "Message: <strong> " . $msg . "</strong>";
                                                echo "<br/><br/>";
                                                //echo "</div>";
                                                //echo "<div class='6u 12u$(4)'>"; 
                                                echo "<div class\"btn-group\">";                   
                                                echo "<p><a class=\"btn btn-success\" type=\"button\" href=\"/php/accept_request.php?id=" . $request->getID() . "\">Accept Request</a></p>";
                                                echo "<p><a class=\"btn btn-danger\" type=\"button\" href=\"/php/cancel_request.php?id=" . $request->getID() . "\">Refuse Request</a></p>";
                                                //echo "</div>";
                                                echo "<hr/>";
                                                //echo "</div></div>";
                                                echo "</header>";
                                                //echo "</div></section></div></section>";

                                            }
                                        }
                                            }



                                }
                                ?>
                                         
                                </section>
                            </div>
                        
                    </div>
               
            </section>

            <!-- Requests -->

            <section id="two" class="wrapper style2">
                <div class="container" id="requests">
                    <header class="major">
                        <h2>My Requests</h2>
                        <p>View the status of your requests for rides</p>
                        <img src="https://image.flaticon.com/icons/svg/129/129517.svg" height=200 width=200/>
                        <br/><br/>
                        <a href="#"><button class="button alt">Back to Inbox</button></a>
                    </header>
                    
                        <section class="seg">
                            
                                <?php
                                $result = $requestDatabase->getRequestByRider($_SESSION['user_id']);

                                if (sizeof($result) == 0) {
                                    echo "<header class='major'>";
                                    echo "<h2>No Requests</h2>";
                                    echo "</header></section>";
                                } else {
                                    foreach ($result as $request){
                                        $offer = $offerDatabase->getOffer($request->getOfferID());
                                        echo "<header class='major'>";
                                        
                                        //echo "<div class=\"col-md-12\">";
                                        echo "<h3>From <strong>" . $offer->getStartLocation() . " </strong>to<strong> " . $offer->getDestination() . "</strong></h3>";
                                        
                                        echo "<h3>Driver: <strong>" . $offer->getDriverName() . "</strong></h3>";

                                        //echo "<div class=\"row\">";
                                        echo "<h3>Message: ";
                                        $msg = $request->getMsg();
                                        if (empty($msg))
                                            echo "No message left";
                                        else
                                            echo $msg . "</h3>";
                                        //echo "</div>";
                                       
                                        //echo "<div>";
                                        echo "<br/><br/>";
                                        echo "<a class=\"btn btn-primary\" type=\"button\" href=\"/php/offerdetails.php?id=" . $offer->getID() . "\">More details</a>";
                                        //echo "</div>";
                                        //echo "</div>";
                                        
                                        //echo "<div class=\"col-md-1\">";         
                                        echo "&nbsp;&nbsp;";           
                                        echo "<a class=\"btn btn-danger\" type=\"button\" href=\"/php/cancel_request.php?id=" . $request->getID() . "\">Cancel Request</a>";
                                        //echo "</div>";
                                        //echo "</div>";
                                        echo "</div></header></section>";
                                    }
                                }

                                // received
                                /**
                                $result = $requestDatabase->getRequestByDriver($_SESSION['user_id']);

                                if (sizeof($result) == 0) {
                                    echo "<header class='major'>";
                                    echo "<h2>No Requests</h2>";
                                    echo "</header></section>";
                                } else {
                                    foreach ($result as $request){
                                        $offer = $offerDatabase->getOffer($request->getOfferID());
                                        $rider = $userDatabase->getUser($request->getRiderID());
                                        echo "<header class='major'>";
                                        //echo "<div class=\"row\">";
                                        //echo "<div class=\"col-md-10\">";

                                        echo "<h3>From <strong>" . $offer->getStartLocation() . " </strong>to<strong> " . $offer->getDestination() . "</strong></h3>";
                                        
                                        echo "<h3>Rider: <strong>" . $rider->getName() . "</strong></h3>";

                                        echo "</div>";
                                        echo "<div class=\"col-md-2\">";
                                        echo "<p><a class=\"btn btn-primary\" type=\"button\" href=\"/php/offerdetails.php?id=" . $offer->getID() . "\">More details</a></p>";
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
                                        echo "<div class\"btn-group\">";                   
                                        echo "<p><a class=\"btn btn-success\" type=\"button\" href=\"/php/accept_request.php?id=" . $request->getID() . "\">Accept Request</a></p>";
                                        echo "<p><a class=\"btn btn-danger\" type=\"button\" href=\"/php/cancel_request.php?id=" . $request->getID() . "\">Refuse Request</a></p>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                }
                            **/

                                ?>
                                
                            </section>
                        </div>
                        
                    </div>
               
            </section>

    <?php
    include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.html"); 
?>