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
    $userFile = "./../xmls/users.xml";
    $userDatabase = new Database($userFile);

    $offerFile = "./../xmls/offers.xml";
    $offerDatabase = new Database($offerFile);

    $requestFile = "./../xmls/requests.xml";
    $requestDatabase = new Database($requestFile);

    $user = $userDatabase->searchNodes("/list/user", NULL, array("id" => $_SESSION['user_id']));
    if (empty($user)) {
        var_dump($user);
        // header("Location: ./error.php?code=4");
    } else {
        $user = $user[0];
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
            $msgs = $user->notification->children();
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
                echo "<div class=\"col-md-12\" style=\"font-size: small;padding-left: 40px;\">";
                echo $msg->__toString();
                echo "</div>";
                echo "</div>";
                echo "</div>";
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
        <div id="offers" class="panel-collapse collapse">
            <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
        </div>
        </div>
        <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">
            <a data-toggle="collapse" href="#requests">My Requests</a>
            </h4>
        </div>
        <div id="requests" class="panel-collapse collapse">
            <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
        </div>
        </div>
        <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">
            <a data-toggle="collapse" href="#received">Received Requests</a>
            </h4>
        </div>
        <div id="received" class="panel-collapse collapse">
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