<?php
/**
 * Authorizer
 * @package lib-user-auth-oauth2
 * @version 0.0.1
 */

namespace LibUserAuthOauth2\Library;

use LibUserAuthOauth2\Library\Provider;

class Authorizer
    implements
        \LibUser\Iface\Authorizer,
        \LibApp\Iface\Authorizer
{

    private static $provider;
    private static $session;

    static function getProvider(){
        if(!self::$provider)
            self::$provider = new Provider;
        return self::$provider;
    }

    static function getSession(): ?object {
        return self::$session;
    }

    static function hasScope(string $scope): bool {
        
    }

    static function identify(): ?string {
        $server = self::getProvider()->getServer();
        $req    = \OAuth2\Request::createFromGlobals();

        if(!$server->verifyResourceRequest($req))
            return null;

        $token = $server->getAccessTokenData($req);
        if(!$token)
            return null;

        self::$session = (object)[
            'type'      => 'oauth2',
            'expires'   => $token['expires'],
            'token'     => $token['_token']->token,
            'app'       => $token['client_id']
        ];

        if(!$token['user_id'])
            return null;
        return $token['user_id'];
    }

    static function loginById(string $identity): ?array {
        return null;
    }

    static function logout(): void{
        self::getProvider()->revokeToken(self::$session->token);
    }
}