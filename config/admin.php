<?php

return [
    'admin' => [
        'name' => env('ADMIN_NAME', 'Admin Lactona'),
        'email' => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWORD'),
    ],
    'superadmin' => [
        'name' => env('SUPERADMIN_NAME', 'Superadmin Lactona'),
        'email' => env('SUPERADMIN_EMAIL') ?: env('ADMIN_EMAIL'),
        'password' => env('SUPERADMIN_PASSWORD'),
    ],
];
