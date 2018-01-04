<?php
    extract($_POST);
    $file = "offer.json";
    $json = json_decode(file_get_contents($file, TRUE), TRUE);
    var_dump($json);
    $json[] = $_POST;
    $json = json_encode($json);
    file_put_contents($file, $json);
    // header("Location: index.html")
?>