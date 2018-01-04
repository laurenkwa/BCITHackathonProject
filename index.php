<?php
include("includes/header.php");


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
    <div class="jumbotron text-center">
        <h1>Ride Share</h1>
        <p>Needs a ride? find one here!</p>
    </div>
    <div class="container">
        <ul class="nav nav-tabs">
            <li class="active">
                <a data-toggle="tab" href="#driver">Offer a Drive</a>
            </li>
            <li>
                <a data-toggle="tab" href="#passenger">Request a ride</a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="driver" class="tab-pane fade in active">
                <h3>Offer a driver</h3>
                <form class="form-horizontal" id="driverForm" method="post" action="offer_process.php">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="driver_username">Username: </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="driver_username" id="driver_username" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="driver_time">Time: </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="driver_time" id="driver_time" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="driver_start">Start From: </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="driver_start" id="driver_start" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="driver_end">Destination: </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="driver_end" id="driver_end" />
                        </div>
                    </div>
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
                    <input type="hidden" name="driver_lat" id="driver_lat" />
                    <input type="hidden" name="driver_lng" id="driver_lng" />
                    <div class="col-sm-10 col-sm-offset-2">
                        <input type="submit" onMouseDown="getCood();" class="btn btn-success" id="driver_submit">
                    </div>
                </form>
            </div>
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
    include("includes/foot.php");
?>