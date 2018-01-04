<?php



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
        crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp"
        crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    <script src="driverMap.js" type="text/javascript"></script>
    <title>Ride-Share</title>

</head>



<body>
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
                <form class="form-horizontal" id="driverForm" action="" method="post">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="driver_username">Username: </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="driver_username" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="driver_time">Time: </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="driver_time" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="driver_start">Start From: </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="driver_start" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="driver_end">Destination: </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="driver_end" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="driver_seats">No. of seats available: </label>
                        <div class="col-sm-10">
                            <select class="form-control" id="driver_seats">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-10 col-sm-offset-2">
                        <input type="submit" onclick="driverMap();" class="btn btn-success" id="driver_submit">
                    </div>
                </form>
                <iframe id="googleMap" width="100" height="750" frameborder="0" style="border:0" allowfullscreen></iframe>
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
                    <div class="col-sm-10 col-sm-offset-2">
                        <button type="submit" class="btn btn-success" name="passenger_submit" id="passenger_submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>


</html>