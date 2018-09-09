<?php

$api_token = 'http://site.mim/api/auth/oauth2/token';
$api_authorize = 'http://site.mim/api/auth/oauth2/authorize';
$app_id = 1;

# 0
if(isset($_GET['error'])){
    echo '<h1>' . $_GET['error'] . '</h1>';
    if($_GET['error_description'])
        echo '<p>' . $_GET['error_description'] . '</p>';

# 2
}elseif(isset($_GET['state']) && $_GET['state'] == 'authorize'){
    echo '<h1>Success</h1>';
    echo '<ul>';
        echo '<li>Access Token: ' . $_GET['access_token'] . '</li>';
        echo '<li>Expires In: ' . $_GET['expires_in'] . '</li>';
        echo '<li>Type: ' . $_GET['token_type'] . '</li>';
        echo '<li>Scope: ' . $_GET['scope'] . '</li>';
    echo '</ul>';

# 1
}else{
    $queries = [
        'response_type' => 'token',
        'client_id' => $app_id,
        'redirect_uri' => 'http://localhost/oauth2/implicit.php',
        'state' => 'authorize',
        'scope' => 'publish_timeline'
    ];

    $next = $api_authorize . '?' . http_build_query($queries);

    echo '<a href="' . $next . '" id="nexter">Click next to authorize</a>';

    // if #access_token exists, you're arrived
    // as server side can't handler this, we'll use js 
    echo '<script>';
        echo 'if(~location.href.indexOf(\'#\'))';
            echo 'location.href = location.href.replace(\'#\', \'?\');';
    echo '</script>';
}