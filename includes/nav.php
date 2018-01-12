<div class="jumbotron text-center" style="padding: 10px;">
  <br/>
  <a href="index.php"><h1>Ride Share</h1></a>
  <p>Need a ride? Find one here!</p>
  <p><a href="https://ride-share.glitch.me/" target="_blank"><button class = "btn btn-primary" id="slackLink" >Download Slack App</button></a></p>

</div>
<div class="text-center" style="background-color: #efdede;padding: 10px; margin-bottom: 10px;">
  <?php
  if (isset($_SESSION['user_id'])) {
    echo('<p>Welcome to Ride-Share, <span style="font-weight: bold;">' . $_SESSION['user_name'] . '</span>!</p>');
    echo('<p><a href="logout.php" class="btn btn-danger">Log out</a></p>');
  } else {
    echo('<p><a href="https://slack.com/oauth/authorize?client_id=155127176102.293670961635&scope=identity.basic&redirect_uri=https://ride-share.azurewebsites.net/process_login.php"><img src="https://platform.slack-edge.com/img/sign_in_with_slack.png" srcset="https://platform.slack-edge.com/img/sign_in_with_slack.png 1x, https://platform.slack-edge.com/img/sign_in_with_slack@2x.png 2x"></a></p>');
  }
  ?>
</div>