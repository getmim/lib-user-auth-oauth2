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
                ],
                'index' => 1000
            ],
            'app' => [
                'type' => 'INT',
                'attrs' => [
                    'unsigned' => true,
                    'null' => false
                ],
                'index' => 2000
            ],
            'secret' => [
                'type' => 'VARCHAR',
                'length' => 64,
                'attrs' => [
                    'null' => false
                ],
                'index' => 3000
            ],
            'grants' => [
                'type' => 'TEXT',
                'index' => 4000
            ],
            'scopes' => [
                'type' => 'TEXT',
                'index' => 5000
            ],
            'redirect' => [
                'type' => 'VARCHAR',
                'length' => 250,
                'attrs' => [
                    'null' => false
                ],
                'index' => 6000
            ],
            'updated' => [
                'type' => 'TIMESTAMP',
                'attrs' => [
                    'default' => 'CURRENT_TIMESTAMP',
                    'update' => 'CURRENT_TIMESTAMP'
                ],
                'index' => 7000
            ],
            'created' => [
                'type' => 'TIMESTAMP',
                'attrs' => [
                    'default' => 'CURRENT_TIMESTAMP'
                ],
                'index' => 8000
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
