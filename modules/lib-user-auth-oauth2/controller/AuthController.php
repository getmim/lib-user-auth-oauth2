<?php
/**
 * AuthController
 * @package lib-user-auth-oauth2
 * @version 0.0.1
 */

namespace LibUserAuthOauth2\Controller;

use LibUserAuthOauth2\Library\Authorizer;

class AuthController extends \Api\Controller
{

    private function errorView($params){
        $this->res->render('user/auth/oauth2/error', $params);
        $this->res->send();
    }

    public function authorizeAction() {
        $req = \OAuth2\Request::createFromGlobals();
        $res = new \OAuth2\Response();

        $server = Authorizer::getProvider()->getServer();

        if(!$server->validateAuthorizeRequest($req, $res)){
            if($res->getStatusCode() != 302)
                return $this->errorView($res->getParameters());
            return $res->send();
        }

        $storage = Authorizer::getProvider()->getStorage();
        $app = $storage->getLastClient()['_app'];

        if(!$this->user->isLogin()){
            $router = $this->config->libUserAuthOauth2->loginRoute;
            $next = $this->router->to($router, [], [
                'next'      => $this->req->url,
                'app'       => $app->id,
                'app_type'  => 'oauth2',
                'scope'     => $this->req->getQuery('scope')
            ]);

            return $this->res->redirect($next);
        }

        if($this->req->getQuery('deny') == 1){
            $server->handleAuthorizeRequest($req, $res, false);
            return $res->send();
        }

        if($this->req->getPost('allow') == 1){
            $server->handleAuthorizeRequest($req, $res, true, $this->user->id);
            return $res->send();
        }

        $scopes = $this->req->getQuery('scope');
        if($scopes){
            $all_scopes = $storage->getAllScopes();
            $used_scopes = [];
            $scopes = explode(' ', $scopes);
            foreach($scopes as $scope){
                if(isset($all_scopes[$scope]))
                    $used_scopes[] = $all_scopes[$scope];
            }
            $scopes = $used_scopes;
        }

        $this->res->render('user/auth/oauth2/authorize', [
            'app'    => $app,
            'scopes' => $scopes,
            'cancel' => $this->req->url . '&deny=1'
        ]);
        
        return $this->res->send();
    }

    public function tokenAction() {
        Authorizer::getProvider()
            ->getServer()
            ->handleTokenRequest(
                \OAuth2\Request::createFromGlobals()
            )
            ->send();
    }
}