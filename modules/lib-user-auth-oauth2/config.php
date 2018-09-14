<?php

return [
    '__name' => 'lib-user-auth-oauth2',
    '__version' => '0.0.1',
    '__git' => 'git@github.com:getmim/lib-user-auth-oauth2.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'http://iqbalfn.com/'
    ],
    '__files' => [
        'modules/lib-user-auth-oauth2' => ['install','update','remove'],
        'theme/api/user/auth/oauth2' => ['install','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'api' => NULL
            ],
            [
                'lib-app' => NULL
            ],
            [
                'lib-user' => NULL
            ],
            [
                'lib-model' => NULL
            ],
            [
                'lib-view' => NULL
            ]
        ],
        'optional' => []
    ],
    'autoload' => [
        'classes' => [
            'OAuth2' => [
                'type' => 'psr4',
                'base' => 'modules/lib-user-auth-oauth2/third-party/OAuth2'
            ],
            'LibUserAuthOauth2\\Controller' => [
                'type' => 'file',
                'base' => 'modules/lib-user-auth-oauth2/controller'
            ],
            'LibUserAuthOauth2\\Library' => [
                'type' => 'file',
                'base' => 'modules/lib-user-auth-oauth2/library'
            ],
            'LibUserAuthOauth2\\Model' => [
                'type' => 'file',
                'base' => 'modules/lib-user-auth-oauth2/model'
            ]
        ],
        'files' => []
    ],
    'libUser' => [
        'authorizers' => [
            'oauth2' => 'LibUserAuthOauth2\\Library\\Authorizer'
        ]
    ],
    'routes' => [
        'api' => [
            'apiUserAuthOAuth2AccessToken' => [
                'path' => [
                    'value' => '/auth/oauth2/token'
                ],
                'handler' => 'LibUserAuthOauth2\\Controller\\Auth::token',
                'method' => 'POST|GET'
            ],
            'apiUserAuthOAuth2Authorize' => [
                'path' => [
                    'value' => '/auth/oauth2/authorize'
                ],
                'handler' => 'LibUserAuthOauth2\\Controller\\Auth::authorize',
                'method' => 'POST|GET'
            ],
            'apiUserAuthOAuth2Revoke' => [
                'path' => [
                    'value' => '/auth/oauth2/revoke'
                ],
                'handler' => 'LibUserAuthOauth2\\Controller\\Auth::revoke',
                'method' => 'POST'
            ]
        ]
    ],
    'libUserAuthOauth2' => [
        'loginRoute' => 'siteLogin',
        'tokenLifetime' => 3600,
        'refreshTokenLifetime' => 1296000,
        'methods' => [
            'authorization_code' => TRUE,
            'implicit' => TRUE,
            'password' => TRUE,
            'client_credentials' => TRUE,
            'refresh_token' => TRUE
        ]
    ]
];