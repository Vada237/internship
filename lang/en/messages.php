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
        'notfound' => 'Organization not found'
    ],
    'roles' => [
        'delete' => [
            'success' => 'Role has been deleted'
        ],
        'notfound' => 'Role not found'
    ]
];
