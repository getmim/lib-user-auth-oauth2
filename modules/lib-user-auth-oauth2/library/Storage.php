<?php
/**
 * Storage
 * @package lib-user-auth-oauth2
 * @version 0.0.1
 */

namespace LibUserAuthOauth2\Library;

use LibApp\Model\App;
use LibUserAuthOauth2\Model\UserAuthOauth2App;
use LibUserAuthOauth2\Model\UserAuthOauth2Code;
use LibUserAuthOauth2\Model\UserAuthOauth2RefreshToken;
use LibUserAuthOauth2\Model\UserAuthOauth2Session;
use LibUserAuthOauth2\Model\UserAuthOauth2Scope;

class Storage implements
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
    private $client;
    private $rtoken;
    private $scopes = [];
    private $session;
    private $user;

    public function getLastClient()
    {
        return $this->client;
    }

    public function getAllScopes()
    {
        if (!$this->scopes) {
            $scopes = UserAuthOauth2Scope::get([]);
            if ($scopes) {
                $this->scopes = [];
                foreach ($scopes as $scope) {
                    $this->scopes[$scope->name] = $scope;
                }
            }
        }

        return $this->scopes;
    }

    // AccessTokenInterface
    public function getAccessToken($token)
    {
        if ($this->session && $this->session['_token']->token == $token) {
            return $this->session;
        }
        
        $atoken = UserAuthOauth2Session::getOne(['token'=>$token]);
        if (!$atoken) {
            return false;
        }

        $this->session = [
            '_token'    => $atoken,
            'expires'   => strtotime($atoken->expires),
            'client_id' => $atoken->app,
            'user_id'   => $atoken->user,
            'scope'     => $atoken->scopes,
            'id_token'  => null
        ];

        return $this->session;
    }
    public function setAccessToken($token, $client, $user, $expires, $scope = null)
    {
        UserAuthOauth2Session::create([
            'app'     => $client,
            'user'    => $user,
            'token'   => $token,
            'expires' => date('Y-m-d H:i:s', $expires),
            'scopes'  => $scope ? $scope : null
        ]);

        $this->getAccessToken($token);
    }
    public function unsetAccessToken($token)
    {
        $session = $this->getAccessToken($token);
        if (!$session) {
            return;
        }
        UserAuthOauth2Session::remove(['token'=>$token]);
        UserAuthOauth2RefreshToken::remove(['session'=>$session['_token']->id]);
    }

    // ClientInterface
    public function getClientDetails($id)
    {
        if ($this->client && $this->client['_app']->id == $id) {
            return $this->client;
        }

        $oapp = UserAuthOauth2App::getOne(['app'=>$id]);
        if (!$oapp) {
            return false;
        }

        $app = App::getOne(['id'=>$id]);
        if (!$app) {
            return false;
        }

        if ($oapp->grants[0] == '[') {
            $oapp->grants = json_decode($oapp->grants);
        } else {
            $oapp->grants = explode(',', $oapp->grants);
        }

        $this->client = [
            '_app'         => $app,
            '_oapp'        => $oapp,
            'redirect_uri' => $oapp->redirect,
            'client_id'    => $id,
            'grant_types'  => $oapp->grants,
            'user_id'      => null,
            'scope'        => $oapp->scopes
        ];

        return $this->client;
    }

    public function getClientScope($id)
    {
        $client = $this->getClientDetails($id);
        if (!$client) {
            return '';
        }

        return $client['scope'];
    }

    public function checkRestrictedGrantType($id, $grant)
    {
        $client = $this->getClientDetails($id);
        if (!$client) {
            return false;
        }

        return in_array($grant, $client['grant_types']);
    }

    // ClientCredentialsInterface
    public function checkClientCredentials($id, $secret = null)
    {
        $client = $this->getClientDetails($id);
        if (!$client) {
            return false;
        }

        $oapp = $client['_oapp'];
        return $oapp->secret === $secret;
    }
    public function isPublicClient($id)
    {
        return false;
    }

    // AuthorizationCodeInterface
    public function getAuthorizationCode($code)
    {
        $ctoken = UserAuthOauth2Code::getOne(['token'=>$code]);
        if (!$ctoken) {
            return null;
        }

        $result = [
            'client_id' => $ctoken->app,
            'user_id'   => $ctoken->user,
            'expires'   => strtotime($ctoken->expires),
            'redirect_uri' => $ctoken->redirect,
            'scope'     => $ctoken->scopes
        ];

        if ($result['expires'] < time()) {
            $this->expireAuthorizationCode($code);
        }

        return $result;
    }
    public function setAuthorizationCode($code, $client, $user, $redirect, $expires, $scope = null)
    {
        UserAuthOauth2Code::create([
            'token'     => $code,
            'app'       => $client,
            'user'      => $user,
            'redirect'  => $redirect,
            'expires'   => date('Y-m-d H:i:s', $expires),
            'scopes'    => $scope
        ]);
    }
    public function expireAuthorizationCode($code)
    {
        UserAuthOauth2Code::remove(['token'=>$code]);
    }

    // RefreshTokenInterface
    public function getRefreshToken($token)
    {
        if ($this->rtoken && $this->rtoken['refresh_token'] == $token) {
            return $this->rtoken;
        }

        $rtoken = UAORToken::getOne(['token'=>$token]);
        if (!$rtoken) {
            return null;
        }

        $this->rtoken = [
            '_rtoken'       => $rtoken,
            'refresh_token' => $rtoken->token,
            'client_id'     => $rtoken->app,
            'user_id'       => $rtoken->user,
            'expires'       => strtotime($rtoken->expires),
            'scope'         => $rtoken->scopes
        ];

        return $this->rtoken;
    }
    public function setRefreshToken($token, $client, $user, $expires, $scope = null)
    {
        UserAuthOauth2RefreshToken::create([
            'app'     => $client,
            'session' => $this->session['_token']->id,
            'user'    => $user,
            'token'   => $token,
            'expires' => date('Y-m-d H:i:s', $expires),
            'scopes'  => $scope
        ]);
    }
    public function unsetRefreshToken($token)
    {
        if ($this->rtoken && $this->rtoken['_rtoken']->token == $token) {
            $rtoken = $this->rtoken['_rtoken'];
        } else {
            $rtoken = UserAuthOauth2RefreshToken::getOne(['token'=>$token]);
        }
        
        if (!$rtoken) {
            return;
        }

        UserAuthOauth2RefreshToken::remove(['id'=>$rtoken->id]);
        if ($rtoken->session) {
            UAOSession::remove(['id'=>$rtoken->session]);
        }
    }

    // UserCredentialsInterface
    public function checkUserCredentials($name, $password)
    {
        if ($this->user && $this->user['_user']->name == $name) {
            return true;
        }

        $handler = \Mim::$app->config->libUser->handler;
        $user = $handler::getByCredentials($name, $password);
        if (!$user) {
            return false;
        }

        $this->user = [
            '_user'     => $user,
            'user_id'   => $user->id,
            'scope'     => ''
        ];

        return true;
    }
    public function getUserDetails($name)
    {
        return $this->user ?? false;
    }

    // JwtBearerInterface
    public function getClientKey($client_id, $subject)
    {
        deb(__FUNCTION__);
    }

    public function getJti($client_id, $subject, $audience, $expiration, $jti)
    {
        deb(__FUNCTION__);
    }

    public function setJti($client_id, $subject, $audience, $expiration, $jti)
    {
        deb(__FUNCTION__);
    }

    // ScopeInterface
    public function scopeExists($names)
    {
        $this->getAllScopes();

        $names = explode(' ', $names);
        foreach ($names as $name) {
            if (!isset($this->scopes[$name])) {
                return false;
            }
        }

        return true;
    }

    public function getDefaultScope($client_id = null)
    {
        return null;
    }

    // PublicKeyInterface
    public function getPublicKey($client_id = null)
    {
        deb(__FUNCTION__);
    }

    public function getPrivateKey($client_id = null)
    {
        deb(__FUNCTION__);
    }

    public function getEncryptionAlgorithm($client_id = null)
    {
        deb(__FUNCTION__);
    }
}
