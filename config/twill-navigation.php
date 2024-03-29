<?php

return [
    'projects' => [
        'title' => 'Projects',
        'module' => true,
    ],
    'pages' => [
        'title' => 'Pages',
        'module' => true,
    ],
    'cvs' => [
        'title' => 'Cvs',
        'route' => 'admin.cvs',
    ],
    'settings' => [
        'title' => 'Settings',
        'route' => 'admin.settings',
        'params' => ['section' => 'settings'],
        'primary_navigation' => [
            'settings' => [
                'title' => 'Settings',
                'route' => 'admin.settings',
                'params' => ['section' => 'settings']
            ],
        ]
    ],
];
