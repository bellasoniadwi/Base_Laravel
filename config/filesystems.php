<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

        'firebase' => [
            'driver' => 'firebase',
            'project_id' => 'project-sinarindo',
            'client_email' => 'firebase-adminsdk-3bnam@project-sinarindo.iam.gserviceaccount.com',
            'private_key' => '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC/k05fBZ5sN+nk\nU7UQ8bHH9V98R6ewfU2mezSURZQtdmDEbgVFulB2rzrM6WYaHh7xvm8cMcfFtykn\nU7ZVULN0HG23Tbt8CxAO8PXWRs7w4eH9chXW2elrdgLsj27YejzQVJOe+uvUEmeI\nPSHKFr/cI2HupHxSfbTL/0GctDrpSxRHvvw2bfzDH+jwc8bX4OD6wYk8yiIYXOiv\n7F0vXatQPezmWgt5+tMh0hUsULbft5ivPTAT9zo2WF38/8iySlZTf33jOk769gV8\nJ5nVSDPFPIGm4b3wmoWFPgiFTAitEHD/yVmrl3/IFBDqnjL6B8V7r6Kyg1qFXCIR\n8S3lnGIlAgMBAAECggEALn6QQzSIZehODhmYtLOL+6UcFvwHASjwCwsU0DVwyHXV\n92ZiRjF5LMzLXwb4Pjd4OCCJM9ULEHuq87333w3Wd8QchqPJcEn5DD9D0szdlY2k\nvM+O6FV7tpZuED6hXs2P69nS9/8a9B3BXcnEZRkPWFyH/JSUDasvkWDyahc9wveH\nrgFugJS6UIuRBf413cyJgWOfkb2hhdoH/gv8suAUwWz6GWSw+zJkuUmUx1v8Z0qU\ncrWKwq/ytxnnxZeHWBWbCRnMGs3CGPngxvvzIeHrcRRCFBjHEEfAKmWX2hI9ag5k\njh1/Vrt0LKq5JRTj9OmBDwwSbzwzBz28MFRRkw7X8wKBgQDm+BS3knjUtIwwgPrk\n2mYWrdzzVSbnI1LlVwuTPbH1vhY0ZOqXnMcZ7DOrnzPXJHdqCNK4HkXAcFMl1lpr\n34CCmeL5x7aPhYdA47kjI/Ku/GUt66rtMhbTPa2QSGM0ttOTPJE8O/cd+OiVF2AY\nDwFWKhdAjMzpaZQVk/eW5lSf/wKBgQDUVk1yocPXx0cfeZ3Kf8+bNyeKrDQQxbXx\naoCkG1QLn4CUxtMVQlRPJ/s7QTZXFHU2K3tijsMRs7n1+/T+crve5lWOwsK4rc5Y\nKkwhiVQ1s5OKSbCeyMD+zRfE0MoYPYq/m7y3Aux9M3vlzFZV7P1G97PE+V5di8Iz\nCenRduh92wKBgQC4rKlb8kicwlPJQIfbTmkMPy4IfhBiBZN1fnjp3Q2a0MdOU1Zj\nrV5g8sSJt/yqTCUS6kUaJFJfQTCqc07PK+DEDCk40J++9+QiVTKlz0tu8K4x3lpH\n16H6ezl4wPhZoMlg06/IuqWnGGtXMl6KPg0yiOsYmLwK8XkPG8V+qWIGnwKBgB7B\nxYQmRGz8E6ROhHmxm8va6GJg4UXQrbMjfzDGOJ1aZFCooCjDK90vaGfD4XApqXTI\nRZ4YGc91nikwbuNwkSAPczMzqOsBWhNRRSfScZ05vtRYKjpF0BbYdGnw6GUsfO2W\nTHah+MmF7Jtzxsm5g1KFTMfqay3XsbCc7f6GX9TjAoGBALi0S1ajbCdxrR//fBnp\nZ8J+YvO829rHOH2EBlGyqzaf2GjygZBAGBqQhepJiMftVNdXRFHUBB6gpYRUVyYd\nbrSPo+f11TBQZTkOL0/9A202WrtHTZsUKuPQXEwDixtkfDU/UDddofkON/yeUTUg\nMnkECTH6FT3EVQIUkaxp2yaJ\n-----END PRIVATE KEY-----\n',
        ],
        

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
