<?php

    $userInfo = array(
        'userID' => $_POST['userID'],
        'userPass' => $_POST['userPass']
    );

    $url = 'https://web.njit.edu/~mnz4/cs490/alpha/middle/middle-r.php';

    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($userInfo),
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true
    );


    $ch = curl_init();

    // set the appropriate curl options
    curl_setopt_array($ch, $options);
    
    $response = curl_exec($ch);

    if($response === FALSE) {
        echo "Curl error: " . curl_error($ch);
    }

    curl_close($ch);

    echo $response;

?>
