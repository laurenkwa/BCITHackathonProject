<?php
session_start();
$url = 'https://slack.com/api/oauth.access';
$data = array('client_id' => '155127176102.293670961635', 'client_secret' => '27321abc0621b516a63e0bbaf80d390a', 'code' => $_GET['code'], 'redirect_uri' => 'https://ride-share.azurewebsites.net/php/process_login.php');

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

if (isset($_GET['error'])) {
    header('Location: ./error.php?code=4');
    exit();
}

echo('Data Received');
echo('<pre>');
var_dump($result);
echo('</pre>');

// var_dump($result);
// handle the returned JSON object
if ($result['ok']) {
    echo('Logged in Successfully');
    $_SESSION['access_token'] = $result['access_token'];
    $_SESSION['user_name'] = $result['user']['name'];
    $_SESSION['user_id'] = $result['user']['id'];
    $_SESSION['team_id'] = $result['team']['id'];

    $file = "./../xmls/users.xml";
    echo(__LINE__);
    $database = new Database($file);
    echo(__LINE__);

    // add a new offer
    $user = $database->putIfAbsent("user", "id", $_SESSION['user_id']);
    echo(__LINE__);
    $user->addAttribute("name", $_SESSION['user_name']);
    echo(__LINE__);
    $user->addChild("requestlist");
    $user->addChild("receivedlist");
    $user->addChild("notification");
    echo(__LINE__);

    $database->saveDatabase();
    echo(__LINE__);

    echo('Going back to homepage');
    header('Location: ./../index.php');
    exit();
} else {
    var_dump($result);
    header('Location: ./error.php?code=5');
    exit();
}
?>