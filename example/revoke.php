<?php

$api_token = 'http://site.mim/api/auth/oauth2/revoke';
$token = '53af55c7b024fbaad07cbc84388b9c696d6ee58a';

$ch = curl_init($api_token);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'token'    => $token
]);

$result = curl_exec($ch);

$data = json_decode($result);

if(isset($data->error)){
    echo '<h1>' . $data->error . '</h1>';
    if($data->error_description)
        echo '<p>' . $data->error_description . '</p>';
}else{
    echo '<h1>Success</h1>';
}