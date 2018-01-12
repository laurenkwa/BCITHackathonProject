<?php
session_start();
include("includes/header.html");


?>
<?php include("includes/nav.php"); ?>
    
<div class="container">
        <!-- In page navigation tabs -->
        <ul class="nav nav-tabs">
            <li class="active">
                <a data-toggle="tab" href="#offers">Offers</a>
            </li>
            <li>
                <a data-toggle="tab" href="#driver">Offer a Drive</a>
            </li>
        </ul>
        <!-- In page navigation tabs content -->
        <div class="tab-content">
            <!-- Offers tab -->
            <div id="offers" class="tab-pane fade in active">

            <?php include("./includes/offers.php"); ?>
            
            </div>
            <!-- Offering tab -->
            <div id="driver" class="tab-pane fade in">
            <?php if (isset($_SESSION['user_id'])) { ?>
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
            <?php } else {
                echo "<div class=\"row seg\"><div class=\"col-md-12 text-center\">Please Log in to post offer a drive!</div></div>";
            } ?>
        </div>
    </div>
<?php
    include("includes/reserve_modal.html");
    include("includes/footer.html");
?>