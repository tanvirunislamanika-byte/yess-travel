<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Email Addresses
    |--------------------------------------------------------------------------
    |
    | These email addresses will receive notifications for important events
    | such as new bookings, payments, etc.
    |
    */

    'emails' => [
        env('ADMIN_EMAIL_1', 'admin@example.com'),
        env('ADMIN_EMAIL_2', 'support@example.com'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Notification Settings
    |--------------------------------------------------------------------------
    |
    | Configure which types of notifications admins should receive
    |
    */

    'notifications' => [
        'tour_bookings' => true,
        'hotel_bookings' => true,
        'flight_bookings' => true,
        'payments' => true,
        'status_updates' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Role Names
    |--------------------------------------------------------------------------
    |
    | Define the role names that are considered admin roles
    |
    */

    'roles' => [
        'super_admin',
        'admin',
        'manager',
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin User Field Names
    |--------------------------------------------------------------------------
    |
    | Define the database field names that indicate admin status
    |
    */

    'fields' => [
        'is_admin' => 'is_admin',
        'role' => 'role',
        'permissions' => 'permissions',
    ],
];
