<?php
return [
    'auth' => [
        'login' => [
            'success' => 'User has been authorized',
            'fail' => 'Invalid login of password'
        ],
        'password' => [
            'reset' => [
                'success' => 'Password reset successfully'
            ]
        ]
    ],
    'user' => [
        'delete' => [
            'success' => 'User has been deleted'
        ],
        'notfound' => 'User not found'
    ],
    'organizations' => [
        'delete' => [
            'success' => 'Organization has been deleted',
        ],
        'update' => [
            'success' => 'Organization name has been updated'
        ],
        'join' => [
            'success' => 'You joined an organization'
        ],
        'notfound' => 'Organization not found'
    ],
    'roles' => [
        'delete' => [
            'success' => 'Role has been deleted'
        ],
        'notfound' => 'Role not found'
    ],
    'mail' => [
        'send' => [
            'success' => 'Mail has been sended'
        ]
    ],
    'projects' => [
        'delete' => [
            'success' => 'Project has been deleted'
        ],
        'join' => [
            'success' => 'You joined an project'
        ]
    ],
    'board' => [
        'templates' => [
            'delete' => [
                'success' => 'Board template has been deleted'
            ]
        ],
        'delete' => [
            'success' => 'Board has been deleted'
        ]
    ],
    'task' => [
        'templates' => [
            'delete' => [
                'success' => 'Task template has been deleted'
            ]
        ],
        'users' => [
            'add' => [
                'success' => 'User has been added'
            ],
            'delete' => [
                'success' => 'User has been deleted'
            ]
        ]
    ],
    'subtask' => [
        'templates' => [
            'delete' => [
                'success' => 'Subtask template has been deleted'
            ]
        ],
        'attributes' => [
            'add' => [
                'success' => 'Attribute has been added'
            ],
            'delete' => [
                'success' => 'Attribute has been deleted'
            ],
            'edit' => [
                'success' => 'Attribute has been updated'
            ]
        ]
    ]
];
