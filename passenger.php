<?php
include("includes/header.php");
?>




<div class="jumbotron text-center">
        <br/>
        <a href="index.php"><h1>Ride Share</h1></a>
        <p>Needs a ride? Find one here!</p>
        <p><a href="https://ride-share.glitch.me/" target="_blank"><button class = "btn btn-primary" id="slackLink" >Download Slack App</button></a></p>
    </div>
<div>
    <div id="map"  style="text-align: center; width: 1000px; height: 750px;"></div>
</div>
<?php
    if(isset($_POST)) {
        echo "Hi, " . $_POST["passenger_username"] . "!";


    extract($_POST);
    $file = "passenger.json";
    $json = json_decode(file_get_contents($file, TRUE), TRUE);
    $json[] = $_POST;
    $json = json_encode($json);
    file_put_contents($file, $json);
    //var_dump($_POST);





    }
    
?>
<script type="text/javascript">
    var locations;
    locations = [{lat : <?php echo $passenger_lat; ?>, lng : <?php echo $passenger_lng; ?>}, {lat : 49.1665898, lng : -123.133569}, {lat : 49.2993349, lng : -122.891689}]
    
    $.ajax({
				url: "http://ride-share.azurewebsites.net/offer",
				type: "GET",
				dataType: 'json',
				error: function (x, y, z) {
					alert(x + '\n' + y + '\n' + z);
				},
				success: function (data) {
                    console.log(data);
				}
			});
    function initMap() {

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 9,
          center: {lat: 49.1665898, lng: -123.133569}
        });

        // Create an array of alphabetical characters used to label the markers.
        var labels = 'A12';

        // Add some markers to the map.
        // Note: The code uses the JavaScript Array.prototype.map() method to
        // create an array of markers based on a given "locations" array.
        // The map() method here has nothing to do with the Google Maps API.
        var markers = locations.map(function(location, i) {
          return new google.maps.Marker({
            position: location,
            label: labels[i % labels.length]
          });
        });

        // Add a marker clusterer to manage the markers.
        var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
      }

</script>
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
    </script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAh-wxnCsW7OZsqkWMHXLFtdjwLXo1PsqY&callback=initMap" type="text/javascript"></script>
<?php
    include("includes/foot.php");
?>