<?php
/**
 * Storage
 * @package lib-user-auth-oauth2
 * @version 0.0.1
 */

namespace LibUserAuthOauth2\Library;

use LibApp\Model\App;
use LibUserAuthOauth2\Model\{
    UserAuthOauth2App as UAOApp,
    UserAuthOauth2Scope as UAOScope,
    UserAuthOauth2Session as UAOSession,
    UserAuthOauth2RefreshToken as UAORToken,
    UserAuthOauth2Code as UAOCode
};

class Storage
    implements 
        \OAuth2\Storage\AccessTokenInterface,
        \OAuth2\Storage\ClientInterface,
        \OAuth2\Storage\ClientCredentialsInterface,
        \OAuth2\Storage\AuthorizationCodeInterface,
        \OAuth2\Storage\RefreshTokenInterface,
        \OAuth2\Storage\UserCredentialsInterface,
        \OAuth2\Storage\JwtBearerInterface,
        \OAuth2\Storage\ScopeInterface,
        \OAuth2\Storage\PublicKeyInterface,
        \OAuth2\Storage\JwtAccessTokenInterface
{
    private $clients = [];
    private $last_client;
    private $scopes = [];
    private $tokens = [];
    private $users = [];

    public function getLastClient(){
        return $this->last_client;
    }

    public function getAllScopes(){
        if(!$this->scopes){
            $scopes = UAOScope::get([]);
            if($scopes){
                $this->scopes = [];
                foreach($scopes as $scope)
                    $this->scopes[$scope->name] = $scope;
            }
        }

        return $this->scopes;
    }

    // AccessTokenInterface
    public function getAccessToken($token){
        if(isset($tokens[$token]))
            return $tokens[$token];
        
        $atoken = UAOSession::getOne(['token'=>$token]);
        if(!$atoken)
            return false;

        $tokens[$token] = [
            '_token'    => $atoken,
            'expires'   => strtotime($atoken->expires),
            'client_id' => $atoken->app,
            'user_id'   => $atoken->user,
            'scope'     => $atoken->scopes,
            'id_token'  => null
        ];

        return $tokens[$token];
    }
    public function setAccessToken($token, $client, $user, $expires, $scope=null){
        UAOSession::create([
            'app'     => $client,
            'user'    => $user,
            'token'   => $token,
            'expires' => date('Y-m-d H:i:s', $expires),
            'scopes'  => $scope ? $scope : NULL
        ]);
    }
    public function unsetAccessToken($token){
        return UAOSession::remove(['token'=>$token]);
    }

    // ClientInterface
    public function getClientDetails($id){
        if(isset($this->clients[$id]))
            return $this->clients[$id];

        $oapp = UAOApp::getOne(['app'=>$id]);
        if(!$oapp)
            return false;

        $app = App::getOne(['id'=>$id]);
        if(!$app)
            return false;

        $this->clients[$id] = $this->last_client = [
            '_app'  => $app,
            '_oapp' => $oapp,

            'redirect_uri' => $oapp->redirect,
            'client_id'    => $id,
            'grant_types'  => $oapp->grants ? explode(',', $oapp->grants) : [],
            'user_id'      => NULL,
            'scope'        => $oapp->scopes
        ];

        return $this->clients[$id];
    }
    public function getClientScope($id){
        $client = $this->getClientDetails($id);
        if(!$client)
            return '';

        return $client['scope'];
    }
    public function checkRestrictedGrantType($id, $grant){
        $client = $this->getClientDetails($id);
        if(!$client)
            return false;

        return in_array($grant, $client['grant_types']);
    }

    // ClientCredentialsInterface
    public function checkClientCredentials($id, $secret=null){
        $client = $this->getClientDetails($id);
        if(!$client)
            return false;

        $oapp = $client['_oapp'];
        return $oapp->secret === $secret;
    }
    public function isPublicClient($id){
        return false;
    }

    // AuthorizationCodeInterface
    public function getAuthorizationCode($code){
        $ctoken = UAOCode::getOne(['token'=>$code]);
        if(!$ctoken)
            return NULL;

        $result = [
            'client_id' => $ctoken->app,
            'user_id'   => $ctoken->user,
            'expires'   => strtotime($ctoken->expires),
            'redirect_uri' => $ctoken->redirect,
            'scope'     => $ctoken->scopes
        ];

        if($result['expires'] < time())
            $this->expireAuthorizationCode($code);

        return $result;
    }
    public function setAuthorizationCode($code, $client, $user, $redirect, $expires, $scope=null){
        UAOCode::create([
            'token'     => $code,
            'app'       => $client,
            'user'      => $user,
            'redirect'  => $redirect,
            'expires'   => date('Y-m-d H:i:s', $expires),
            'scopes'    => $scope
        ]);
    }
    public function expireAuthorizationCode($code){
        UAOCode::remove(['token'=>$code]);
    }

    // RefreshTokenInterface
    public function getRefreshToken($token){
        $rtoken = UAORToken::getOne(['token'=>$token]);
        if(!$rtoken)
            return null;

        return [
            'refresh_token' => $rtoken->token,
            'client_id'     => $rtoken->app,
            'user_id'       => $rtoken->user,
            'expires'       => strtotime($rtoken->expires),
            'scope'         => $rtoken->scopes
        ];
    }
    public function setRefreshToken($token, $client, $user, $expires, $scope=null){
        UAORToken::create([
            'app'   => $client,
            'user'  => $user,
            'token' => $token,
            'expires' => date('Y-m-d H:i:s', $expires),
            'scopes' => $scope
        ]);
    }
    public function unsetRefreshToken($token){
        UAORToken::remove(['token'=>$token]);
    }

    // UserCredentialsInterface
    public function checkUserCredentials($name, $password){
        if(isset($this->users[$name]))
            return true;

        $handler = \Mim::$app->config->libUser->handler;
        $user = $handler::getByCredentials($name, $password);
        if(!$user)
            return false;

        $this->users[$name] = [
            '_user'     => $user,
            'user_id'   => $user->id,
            'scope'     => ''
        ];

        return true;
    }
    public function getUserDetails($name){
        return $this->users[$name] ?? false;
    }

    // JwtBearerInterface
    public function getClientKey($client_id, $subject){
        deb(__FUNCTION__);
    }
    public function getJti($client_id, $subject, $audience, $expiration, $jti){
        deb(__FUNCTION__);
    }
    public function setJti($client_id, $subject, $audience, $expiration, $jti){
        deb(__FUNCTION__);
    }

    // ScopeInterface
    public function scopeExists($names){
        $this->getAllScopes();

        $names = explode(' ', $names);
        foreach($names as $name){
            if(!isset($this->scopes[$name]))
                return false;
        }
        return true;
    }

    public function getDefaultScope($client_id = null){
        return null;
    }

    // PublicKeyInterface
    public function getPublicKey($client_id = null){
        deb(__FUNCTION__);
    }
    public function getPrivateKey($client_id = null){
        deb(__FUNCTION__);
    }
    public function getEncryptionAlgorithm($client_id = null){
        deb(__FUNCTION__);
    }
}