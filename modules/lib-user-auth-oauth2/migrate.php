<?php

return [
    'LibUserAuthOauth2\\Model\\UserAuthOauth2Code' => [
        'fields' => [
            'id' => [
                'type' => 'INT',
                'attrs' => [
                    'unsigned' => true,
                    'primary_key' => true,
                    'auto_increment' => true 
                ]
            ],
            'app' => [
                'type' => 'INT',
                'attrs' => [
                    'unsigned' => true,
                    'null' => false 
                ]
            ],
            'user' => [
                'type' => 'INT',
                'attrs' => [
                    'unsigned' => true
                ]
            ],
            'token' => [
                'type' => 'VARCHAR',
                'length' => 64,
                'attrs' => [
                    'null' => false,
                    'unique' => true
                ]
            ],
            'redirect' => [
                'type' => 'VARCHAR',
                'length' => 250,
                'attrs' => [
                    'null' => false
                ]
            ],
            'expires' => [
                'type' => 'DATETIME',
                'attrs' => [
                    'null' => false
                ]
            ],
            'scopes' => [
                'type' => 'TEXT'
            ],
            'created' => [
                'type' => 'TIMESTAMP',
                'attrs' => [
                    'default' => 'CURRENT_TIMESTAMP'
                ]
            ]
        ]
    ],
    'LibUserAuthOauth2\\Model\\UserAuthOauth2RefreshToken' => [
        'fields' => [
            'id' => [
                'type' => 'INT',
                'attrs' => [
                    'unsigned' => true,
                    'primary_key' => true,
                    'auto_increment' => true 
                ]
            ],
            'app' => [
                'type' => 'INT',
                'attrs' => [
                    'unsigned' => true,
                    'null' => false 
                ]
            ],
            'user' => [
                'type' => 'INT',
                'attrs' => [
                    'unsigned' => true
                ]
            ],
            'session' => [
                'type' => 'INT',
                'attrs' => [
                    'unsigned' => true,
                    'null' => false
                ]
            ],
            'token' => [
                'type' => 'VARCHAR',
                'length' => 64,
                'attrs' => [
                    'null' => false,
                    'unique' => true
                ]
            ],
            'expires' => [
                'type' => 'DATETIME',
                'attrs' => [
                    'null' => false
                ]
            ],
            'scopes' => [
                'type' => 'TEXT'
            ],
            'created' => [
                'type' => 'TIMESTAMP',
                'attrs' => [
                    'default' => 'CURRENT_TIMESTAMP'
                ]
            ]
        ]
    ],
    'LibUserAuthOauth2\\Model\\UserAuthOauth2Scope' => [
        'fields' => [
            'id' => [
                'type' => 'INT',
                'attrs' => [
                    'unsigned' => true,
                    'primary_key' => true,
                    'auto_increment' => true 
                ]
            ],
            'name' => [
                'type' => 'VARCHAR',
                'length' => 100,
                'attrs' => [
                    'null' => false,
                    'unique' => true
                ]
            ],
            'about' => [
                'type' => 'TEXT'
            ],
            'created' => [
                'type' => 'TIMESTAMP',
                'attrs' => [
                    'default' => 'CURRENT_TIMESTAMP'
                ]
            ]
        ]
    ],
    'LibUserAuthOauth2\\Model\\UserAuthOauth2Session' => [
        'fields' => [
            'id' => [
                'type' => 'INT',
                'attrs' => [
                    'unsigned' => true,
                    'primary_key' => true,
                    'auto_increment' => true 
                ]
            ],
            'app' => [
                'type' => 'INT',
                'attrs' => [
                    'unsigned' => true,
                    'null' => false 
                ]
            ],
            'user' => [
                'type' => 'INT',
                'attrs' => [
                    'unsigned' => true
                ]
            ],
            'token' => [
                'type' => 'VARCHAR',
                'length' => 64,
                'attrs' => [
                    'null' => false,
                    'unique' => true
                ]
            ],
            'expires' => [
                'type' => 'DATETIME',
                'attrs' => [
                    'null' => false
                ]
            ],
            'scopes' => [
                'type' => 'TEXT'
            ],
            'created' => [
                'type' => 'TIMESTAMP',
                'attrs' => [
                    'default' => 'CURRENT_TIMESTAMP'
                ]
            ]
        ]
    ],

    'LibUserAuthOauth2\\Model\\UserAuthOauth2App' => [
        'fields' => [
            'id' => [
                'type' => 'INT',
                'attrs' => [
                    'unsigned' => true,
                    'primary_key' => true,
                    'auto_increment' => true 
                ]
            ],
            'app' => [
                'type' => 'INT',
                'attrs' => [
                    'unsigned' => true,
                    'null' => false 
                ]
            ],
            'secret' => [
                'type' => 'VARCHAR',
                'length' => 64,
                'attrs' => [
                    'null' => false
                ]
            ],
            'grants' => [
                'type' => 'TEXT'
            ],
            'scopes' => [
                'type' => 'TEXT'
            ],
            'redirect' => [
                'type' => 'VARCHAR',
                'length' => 250,
                'attrs' => [
                    'null' => false
                ]
            ],
            'created' => [
                'type' => 'TIMESTAMP',
                'attrs' => [
                    'default' => 'CURRENT_TIMESTAMP'
                ]
            ]
        ],
        'indexes' => [
            'by_app' => [
                'fields' => [
                    'app' => []
                ]
            ]
        ]
    ]
];