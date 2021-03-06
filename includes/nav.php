<header id="header">
<h1><a href="/index.php">Ride Share</a></h1>
<nav id="nav">
    <ul>
        <li><a href="/index.php">Home</a></li>
        <?php if (isset($_SESSION['user_id'])) { ?>
            <li><a href="/php/userpage.php#inbox">Inbox</a></li>
            <li><a href="/index.php#newRide">New Ride</a></li>
            <li><a href="/php/logout.php">Logout</a></li>
        <?php } ?>
        <li><a href="https://ride-share.glitch.me/" class="button special">Download Slack App</a></li>
    </ul>
</nav>
</header>
<?php 
if (isset($_SESSION['user_id'])) {
    echo('<section id="banner"><h2>Ride Share</h2><p>Welcome, ' . $_SESSION['user_name'] . '!</p></section>');
} else {
    echo('<section id="banner"><h2>Ride Share</h2><p><a href="https://slack.com/oauth/authorize?client_id=155127176102.293670961635&scope=identity.basic&redirect_uri=https://ride-share.azurewebsites.net/php/process_login.php"><img src="https://platform.slack-edge.com/img/sign_in_with_slack.png" srcset="https://platform.slack-edge.com/img/sign_in_with_slack.png 1x, https://platform.slack-edge.com/img/sign_in_with_slack@2x.png 2x"></a></p></section>');
}
?>