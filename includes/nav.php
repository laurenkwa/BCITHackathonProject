<div class="jumbotron text-center">
  <br/>
  <a href="index.php"><h1>Ride Share</h1></a>
  <p>Need a ride? Find one here!</p>
  <?php
  if (isset($_SESSION['access_token'])) {
    $username = $_SESSION['user_name'];
    echo('<p>Welcome to Ride-Share, $username!</p>');
  } else {
    echo('<p><a href="https://slack.com/oauth/authorize?client_id=293788574964.293935676385&amp;scope=identity.basic&amp;redirect_uri=https%3A%2F%2Fride-share.azurewebsites.net%2Fprocess_login.php"><img src="https://platform.slack-edge.com/img/sign_in_with_slack.png" srcset="https://platform.slack-edge.com/img/sign_in_with_slack.png 1x, https://platform.slack-edge.com/img/sign_in_with_slack@2x.png 2x"></a></p>');
  }
  ?>
  <p><a href="https://ride-share.glitch.me/" target="_blank"><button class = "btn btn-primary" id="slackLink" >Download Slack App</button></a></p>

</div>