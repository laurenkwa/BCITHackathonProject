<?php 
    include("./../includes/header.html");

    $code = isset($_GET['code']) ? $_GET['code'] : 0;
?>
<meta http-equiv="refresh" content="3;url=./../index.php" />
<div class="container text-center">
    <div class="jumbotron">
        <a href="index.php"><h1>Ride Share</h1></a>
        <p>
        <?php 
            switch ($code) {
                case 0:
                    echo "What brings you here? This is an unusual error";
                    break;
                case 1:
                    echo "You are not logged in";
                    break;
                case 2:
                    echo "Invalid arguments, please try again";
                    break;
                case 3:
                    echo "Weird, the webpage you are looking for is not found";
                    break;
                case 4:
                    echo "Fatal error, please contact the adminstrator as soon as possible";
                    break;
                case 5:
                    echo "Unable to sign in with Slack";
                    break;
                case 6:
                    echo "You have already reserved a seat for this ride.";
                    break;
                case 7:
                    echo "You cannot reserve a seat for your own offer";
                    break;
                case 8:
                    echo "Access Denied";
                    break;
                default:
                    echo "What brings you here? This is an unusual error";
            }
        ?>
        </p>
        <h3><a href="./../index.php">You will be redirected to the homepage in 3 seconds, Click here if it's taking too long</a></h3>
    </div>
</div>

<?php include("./../includes/footer.html"); ?>