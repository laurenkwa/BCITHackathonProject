<?php
session_start();
$url = 'https://slack.com/api/oauth.access';
$data = array('client_id' => '293788574964.293935676385', 'client_secret' => '6c57ee01b0601eca4c39633d27277492', 'code' => $_GET['code']);

// access OAuth
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$result = json_decode($result, TRUE);

// var_dump($result);
// handle the returned JSON object
if ($result['ok']) {
    $_SESSION['access_token'] = $result['access_token'];
    $_SESSION['user_name'] = $result['user']['name'];
    $_SESSION['user_id'] = $result['user']['id'];
    $_SESSION['team_id'] = $result['team']['id'];
}
?>