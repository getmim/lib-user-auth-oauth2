<?php

$api_token = 'http://site.mim/api/auth/oauth2/token';
$app_id = 1;
$app_secret = 'secret';
$refresh_token = 'd4d8031fd711849d23400a6910c282a8db12d184';

$ch = curl_init($api_token);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'grant_type'    => 'refresh_token',
    'client_id'     => $app_id,
    'client_secret' => $app_secret,
    'refresh_token' => $refresh_token,
    'scope'         => 'publish_timeline read_friends' // optional
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
        echo '<li>Refresh Token: ' . $data->refresh_token . '</li>';
    echo '</ul>';
}