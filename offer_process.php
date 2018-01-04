<?php
    include("includes/header.php");
    extract($_POST);
    $file = "offer.js";
    $json = json_decode(file_get_contents($file, TRUE), TRUE);
    $json[] = $_POST;
    $json = json_encode($json);
    file_put_contents($file, $json);
    var_dump($_POST);
?>
<div class="jumbotron text-center">
        <br/>
        <a href="index.php"><h1>Ride Share</h1></a>
        <p>Needs a ride? Find one here!</p>
        <p><a href="https://ride-share.glitch.me/" target="_blank"><button class = "btn btn-primary" id="slackLink" >Download Slack App</button></a></p>
</div>

<script>
    var origin = "<?php echo $driver_start; ?>";
    var destination = "<?php echo $driver_end; ?>";
    var source ="https://www.google.com/maps/embed/v1/directions?origin=" + origin + "&destination=" + destination + "&key=AIzaSyAh-wxnCsW7OZsqkWMHXLFtdjwLXo1PsqY";
    $(function() {
        $("#googleMap").attr("src", source);
    });
</script>
<div style="text-align: center">
    <iframe id="googleMap" width="1000" height="750" frameborder="0" style="border:0" allowfullscreen></iframe> 
</div>
<?php
    include("includes/header.php");
?>