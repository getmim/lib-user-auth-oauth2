<?php

return [
    '__name' => 'lib-user-auth-oauth2',
    '__version' => '0.2.0',
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
                'api' => null
            ],
            [
                'lib-app' => null
            ],
            [
                'lib-user' => null
            ],
            [
                'lib-model' => null
            ],
            [
                'lib-view' => null
            ],
            [
                'lib-enum' => null
            ],
            [
                'lib-formatter' => null
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
            'authorization_code' => true,
            'implicit' => true,
            'password' => true,
            'client_credentials' => true,
            'refresh_token' => true
        ]
    ],
    'libEnum' => [
        'enums' => [
            'oauth2-app.grants' => [
                'authorization_code' => 'authorization_code',
                'client_credentials' => 'client_credentials',
                'implicit' => 'implicit',
                'password' => 'password',
                'refresh_token' => 'refresh_token',
                'revoke' => 'revoke'
            ]
        ]
    ],
    'libForm' => [
        'forms' => [
            'admin.app.edit' => [
                'secret' => [
                    'type' => 'text',
                    'label' => 'Secret',
                    'rules' => [
                        'required' => true
                    ]
                ],
                'grants' => [
                    'label' => 'Grants',
                    'type' => 'checkbox-group',
                    'rules' => [
                        'required' => true,
                        'enum' => 'oauth2-app.grants'
                    ]
                ],
                'scopes' => [
                    'label' => 'Scopes',
                    'type' => 'checkbox-group',
                    'rules' => []
                ],
                'redirect' => [
                    'type' => 'url',
                    'label' => 'Redirect',
                    'rules' => [
                        'required' => true,
                        'url' => true
                    ]
                ]
            ]
        ]
    ],
    'libFormatter' => [
        'formats' => [
            'oauth2-app' => [
                'id' => [
                    'type' => 'number'
                ],
                'app' => [
                    'type' => 'object',
                    'model' => [
                        'name' => 'LibApp\\Model\\App',
                        'field' => 'id',
                        'type' => 'number'
                    ],
                    'format' => 'app'
                ],
                'secret' => [
                    'type' => 'text'
                ],
                'grants' => [
                    'type' => 'json'
                ],
                'scopes' => [
                    'type' => 'json'
                ],
                'redirect' => [
                    'type' => 'text'
                ],
                'updated' => [
                    'type' => 'date'
                ],
                'created' => [
                    'type' => 'date'
                ]
            ]
        ]
    ]
];
