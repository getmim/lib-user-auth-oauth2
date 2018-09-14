<?php
/**
 * Provider
 * @package lib-user-auth-oauth2
 * @version 0.0.1
 */

namespace LibUserAuthOauth2\Library;

use LibUserAuthOauth2\Library\Storage;
use LibUserAuthOauth2\Model\UserAuthOauth2Session as UAOSession;

class Provider
{
    private $server;
    private $storage;

    public function __construct(){
        $mim = &\Mim::$app;

        $storage = $this->storage = new Storage;

        $opts = [
            'access_lifetime'                   => $mim->config->libUserAuthOauth2->tokenLifetime,
            'refresh_token_lifetime'            => $mim->config->libUserAuthOauth2->refreshTokenLifetime,
            'allow_public_clients'              => false,
            'always_issue_new_refresh_token'    => true,
        ];

        if($mim->config->libUserAuthOauth2->methods->implicit)
            $opts['allow_implicit'] = true;

        $this->server = new \OAuth2\Server($storage, $opts);
    }

    public function getServer(){
        return $this->server;
    }

    public function getStorage(){
        return $this->storage;
    }

    public function revokeToken($token){
        $this->storage->unsetAccessToken($token);
    }
}