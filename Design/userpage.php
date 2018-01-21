<?php
    session_start();
    include("includes/header.html");
    include("includes/nav.php");
?>
    <style>
        <?php include("css/ride-share.css"); ?>
    </style>
<?php
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

    
    ?>

<section id="one" class="wrapper style1 special">
                <div class="container" id="inbox">
                    <header class="major">
                        <h2>Welcome to your Inbox!</h2>
                        <br/><br/>
                        <button class="button alt"><a href="index.php">Back to Home</a></button> 
                        
                    </header>
                    <div class="row 150%">
                        <div class="4u 12u$(medium)">
                            <section class="box">
                                <img src="https://image.flaticon.com/icons/svg/321/321817.svg" height="100" width="100" />
                                <br/><br/>
                                <h1><a href="userpage.php#notifications">Notifications</a></h1>
                                <br/><br/>
                                <p>Check out what's new!</p>
                            </section>
                        </div>
                        <div class="4u 12u$(medium)">
                            <section class="box">
                                <img src="https://image.flaticon.com/icons/svg/129/129517.svg" height="98" width="95" />
                                <br/><br/>
                                <h1><a href="userpage.php#requests">My Requests</a></h1>
                                <br/><br/>
                                <p>View the status of your requests</p>
                            </section>
                        </div>
                        <div class="4u$ 12u$(medium)">
                            <section class="box">
                                <img src="https://image.flaticon.com/icons/svg/254/254048.svg" height=100 width="95" />
                                <br/><br/>
                                <h1><a href="userpage.php#offers">Offers</a></h1>
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
                            <button class="button alt"><a href="userpage.php">Back to Inbox</a></button>   
                        </header>
                        <section>
                            <div class="row">
                                <section>
                                    <?php
                                        $msgs = $user->getAllNotification();
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
                            <button><a href="userpage.php">Back to Inbox</a></button>   
                        </header>
                        <section>
                            <div class="row">
                                <section class="container">
                                    <?php
                                        $msgs = $user->getAllNotification();
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
                                        ?>
                                         
                                </section>
                            </div>
                        </section>
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
                           <button class="button alt"><a href="userpage.php">Back to Inbox</a></button>  
                        </header>
                        <section>
                            <div class="row">
                                <section class="3u 6u(medium) 12u$(xsmall) profile">
                                    <?php
                                        $msgs = $user->getAllNotification();
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
                                        ?>
                                </section>
                            </div>
                        </section>
                </div>
               
            </section>

    <?php
    include("./../includes/footer.html"); 
?>