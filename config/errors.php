<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Error Reporting
    |--------------------------------------------------------------------------
    |
    | This configuration controls which errors are reported during the
    | application lifecycle. You can set this to E_ALL in development
    | but hide deprecation warnings in production.
    |
    */

    'error_reporting' => env('APP_ENV') === 'production' 
        ? E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED 
        : E_ALL,

    /*
    |--------------------------------------------------------------------------
    | Display Errors
    |--------------------------------------------------------------------------
    |
    | This configuration controls whether errors are displayed to the user.
    | In production, you should always set this to false.
    |
    */

    'display_errors' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Log Errors
    |--------------------------------------------------------------------------
    |
    | This configuration controls whether errors are logged.
    |
    */

    'log_errors' => true,

    /*
    |--------------------------------------------------------------------------
    | Log Errors Max Length
    |--------------------------------------------------------------------------
    |
    | This configuration controls the maximum length of logged errors.
    |
    */

    'log_errors_max_len' => 1024,

];