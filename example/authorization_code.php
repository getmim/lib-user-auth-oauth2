<?php

$api_token = 'http://site.mim/api/auth/oauth2/token';
$api_authorize = 'http://site.mim/api/auth/oauth2/authorize';
$app_id = 1;
$app_secret = 'secret';

// 0
if(isset($_GET['error'])){
    echo '<h1>' . $_GET['error'] . '</h1>';
    if($_GET['error_description'])
        echo '<p>' . $_GET['error_description'] . '</p>';

# 2
}elseif(isset($_GET['state']) && $_GET['state'] == 'authorize'){
    $code = $_GET['code'];

    $ch = curl_init($api_token);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'grant_type'    => 'authorization_code',
        'client_id'     => $app_id,
        'client_secret' => $app_secret,
        'code'          => $code,
        'redirect_uri'  => 'http://localhost/oauth2/authorization_code.php'
    ]);

    $result = curl_exec($ch);

    $data = json_decode($result);

    if(isset($data->error)){
        echo '<h1>' . $data->error . '</h1>';
        if($data->error_description)
            echo '<p>' . $data->error_description . '</p>';
    }else{
        echo '<h1>Success</h1>';
        echo '<ul>';
            echo '<li>Access Token: ' . $data->access_token . '</li>';
            echo '<li>Expires In: ' . $data->expires_in . '</li>';
            echo '<li>Type: ' . $data->token_type . '</li>';
            echo '<li>Scope: ' . $data->scope . '</li>';
        echo '</ul>';
    }

# 1
}else{
    $queries = [
        'response_type' => 'code',
        'client_id' => $app_id,
        'redirect_uri' => 'http://localhost/oauth2/authorization_code.php',
        'state' => 'authorize',
        'scope' => 'publish_timeline'
    ];

    $next = $api_authorize . '?' . http_build_query($queries);

    echo '<a href="' . $next . '" id="nexter">Click next to authorize</a>';

    // // if #access_token exists, you're arrived
    // // as server side can't handler this, we'll use js 
    // echo '<script>';
    //     echo 'if(~location.href.indexOf(\'#\'))';
    //         echo 'location.href = location.href.replace(\'#\', \'?\');';
    // echo '</script>';
}