<?php
session_start();
include("includes/header.html");


?>
<script>
function getCood(){
    var origin = document.getElementById("driver_start").value;
    $(function () {
      var url = "https://maps.googleapis.com/maps/api/geocode/json?address=" + origin + "&key=AIzaSyAh-wxnCsW7OZsqkWMHXLFtdjwLXo1PsqY";
			
        $.ajax({
            url: url,
            type: "GET",
            dataType: 'json',
            error: function (x, y, z) {
                alert(x + '\n' + y + '\n' + z);
            },
            success: function (data) {
                var lat = data.results[0].geometry.location.lat;
                var lng = data.results[0].geometry.location.lng;
                $("#driver_lat").attr("value", lat);
                $("#driver_lng").attr("value", lng);
            }
        });
    })
}
    
function getCoodP(){
    var origin = document.getElementById("passenger_start").value;
    $(function () {
      var url = "https://maps.googleapis.com/maps/api/geocode/json?address=" + origin + "&key=AIzaSyAh-wxnCsW7OZsqkWMHXLFtdjwLXo1PsqY";
			
        $.ajax({
            url: url,
            type: "GET",
            dataType: 'json',
            error: function (x, y, z) {
                alert(x + '\n' + y + '\n' + z);
            },
            success: function (data) {
                var lat = data.results[0].geometry.bounds.northeast.lat;
                var lng = data.results[0].geometry.bounds.northeast.lng;
                $("#passenger_lat").attr("value", lat);
                $("#passenger_lng").attr("value", lng);
            }
        });
    })
}
</script>
<?php include("includes/nav.php"); ?>
    
    <div class="container">
        <!-- In page navigation tabs -->
        <ul class="nav nav-tabs">
            <li class="active">
                <a data-toggle="tab" href="#offers">Offers</a>
            </li>
            <li>
                <a data-toggle="tab" href="#requests">Requests</a>
            </li>
            <li>
                <a data-toggle="tab" href="#driver">Offer a Drive</a>
            </li>
            <li>
                <a data-toggle="tab" href="#passenger">Request a ride</a>
            </li>
        </ul>
        <!-- In page navigation tabs content -->
        <div class="tab-content">
            <!-- Offers tab -->
            <div id="offers" class="tab-pane fade in active">
            <?php
                $offer_file = "offer.json";
                $offers = json_decode(file_get_contents($offer_file, TRUE), TRUE);
                $i = 0;
                if (sizeof($offers) == 0) {
                    echo("No offer has been posted");
                } else {
                foreach ($offers as $offer) {
                    $i += 1;
                    echo "<br/>Driver name: " . $offer['driver_username'];
                    echo "<br/><br/>";
                    echo "Ride schedule: " . $offer['driver_time'];
                    ?>
                    <iframe id="googleMap<?php echo($i); ?>" width="1000" height="750" frameborder="0" style="border:0" allowfullscreen></iframe>
                    <script>
                        $(function() {
                            $("#googleMap<?php echo($i); ?>").attr("src", "https://www.google.com/maps/embed/v1/directions?origin=" +
                            <?php echo("\"" . $offer['driver_start'] . "\""); ?> +
                            "&destination=" +
                            <?php echo("\"" . $offer['driver_end'] . "\""); ?> +
                            "&key=AIzaSyAh-wxnCsW7OZsqkWMHXLFtdjwLXo1PsqY");
                        });
                    </script>
                    <?php
                }}
            ?>
            
            </div>
            <!-- Requests tab -->
            <div id="requests" class="tab-pane fade in">
            <?php
                $request_file = "passenger.json";
                $requests = json_decode(file_get_contents($request_file, TRUE), TRUE);
                $j = 0;
                if (sizeof($requests) == 0) {
                    echo("No request has been posted");
                } else {
                foreach ($requests as $request) {
                    $j += 1;
                    echo "<br/>Passenger name: " . $request['passenger_username'];
                    echo "<br/><br/>";
                    echo "Passenger schedule: " . $request['passenger_time'];
                    ?>
                    <iframe id="googleMap2<?php echo($j); ?>" width="1000" height="750" frameborder="0" style="border:0" allowfullscreen></iframe>
                    <script>
                        $(function() {
                            $("#googleMap2<?php echo($j); ?>").attr("src", "https://www.google.com/maps/embed/v1/directions?origin=" +
                            <?php echo("\"" . $request['passenger_start'] . "\""); ?> +
                            "&destination=" +
                            <?php echo("\"" . $request['passenger_end'] . "\""); ?> +
                            "&key=AIzaSyAh-wxnCsW7OZsqkWMHXLFtdjwLXo1PsqY");
                        });
                    </script>
                    <?php
                }}
            ?>
            </div>
            <!-- Offering tab -->
            <div id="driver" class="tab-pane fade in">
                <h3>Offer a drive</h3>
                <form class="form-horizontal" id="driverForm" method="post" action="php/make_offer.php">
                    <!-- driver_date -->
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="driver_date">Date: </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="date" name="driver_date" id="driver_date" />
                        </div>
                    </div>
                    <script>
                        Date.prototype.toDateInputValue = (function() {
                            var local = new Date(this);
                            local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
                            return local.toJSON().slice(0,10);
                        });
                        document.getElementById('driver_date').defaultValue = new Date().toDateInputValue();
                    </script>
                    <!-- driver_time -->
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="driver_time">Time: </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="time" name="driver_time" id="driver_time" />
                        </div>
                    </div>
                    <script>
                        var d = new Date();
                        var hours = d.getHours().toString();
                        if (hours.length == 1) {
                            hours = "0" + hours;
                        }
                        var minutes = d.getMinutes().toString();
                        if (minutes.length == 1) {
                            minutes = "0" + minutes;
                        }
                        var time = hours + ":" + minutes;
                        console.log(time);
                        document.getElementById('driver_time').defaultValue = time;
                    </script>
                    <!-- driver_start -->
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="driver_start">Start From: </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="driver_start" id="driver_start" required/>
                        </div>
                    </div>
                    <!-- driver_end -->
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="driver_end">Destination: </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="driver_end" id="driver_end" required/>
                        </div>
                    </div>
                    <!-- seats avaliable -->
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="driver_seats">No. of seats available: </label>
                        <div class="col-sm-10">
                            <select class="form-control" name="driver_seats" id="driver_seats">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-10 col-sm-offset-2">
                        <input type="submit" class="btn btn-success" id="driver_submit">
                    </div>
                </form>
            </div>
            <!-- Requesting tab -->
            <div id="passenger" class="tab-pane fade">
                <h3>Request a ride</h3>
                <form class="form-horizontal" id="passengerForm" action="passenger.php" method="post">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="passenger_username">Username: </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="passenger_username" name="passenger_username" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="passenger_time">Time: </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="passenger_time" name="passenger_time" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="passenger_start">Start From: </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="passenger_start" name="passenger_start" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="passenger_end">Destination: </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="passenger_end" name="passenger_end" />
                        </div>
                    </div>
                    <input type="hidden" name="passenger_lat" id="passenger_lat" />
                    <input type="hidden" name="passenger_lng" id="passenger_lng" />
                    <div class="col-sm-10 col-sm-offset-2">
                        <button onMouseDown="getCoodP();" type="submit" class="btn btn-success" id="passenger_submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
    include("includes/footer.html");
?>