<?php
$url = 'https://slack.com/api/oauth.access';
$data = array('client_id' => '293788574964.293935676385', 'client_secret' => '6c57ee01b0601eca4c39633d27277492', 'code' => $_GET['code']);

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === FALSE) { /* Handle error */ }

var_dump($result);
echo($result);
    // header("Location: https://slack.com/api/oauth.access/?client_id=293788574964.293935676385&client_secret=6c57ee01b0601eca4c39633d27277492&code=$_GET['code']");
?>