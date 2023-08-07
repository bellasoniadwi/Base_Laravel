<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'firebase' => [
        'project_id' => 'project-sinarindo',
        'client_email' => 'firebase-adminsdk-3bnam@project-sinarindo.iam.gserviceaccount.com',
        'private_key' => '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC/k05fBZ5sN+nk\nU7UQ8bHH9V98R6ewfU2mezSURZQtdmDEbgVFulB2rzrM6WYaHh7xvm8cMcfFtykn\nU7ZVULN0HG23Tbt8CxAO8PXWRs7w4eH9chXW2elrdgLsj27YejzQVJOe+uvUEmeI\nPSHKFr/cI2HupHxSfbTL/0GctDrpSxRHvvw2bfzDH+jwc8bX4OD6wYk8yiIYXOiv\n7F0vXatQPezmWgt5+tMh0hUsULbft5ivPTAT9zo2WF38/8iySlZTf33jOk769gV8\nJ5nVSDPFPIGm4b3wmoWFPgiFTAitEHD/yVmrl3/IFBDqnjL6B8V7r6Kyg1qFXCIR\n8S3lnGIlAgMBAAECggEALn6QQzSIZehODhmYtLOL+6UcFvwHASjwCwsU0DVwyHXV\n92ZiRjF5LMzLXwb4Pjd4OCCJM9ULEHuq87333w3Wd8QchqPJcEn5DD9D0szdlY2k\nvM+O6FV7tpZuED6hXs2P69nS9/8a9B3BXcnEZRkPWFyH/JSUDasvkWDyahc9wveH\nrgFugJS6UIuRBf413cyJgWOfkb2hhdoH/gv8suAUwWz6GWSw+zJkuUmUx1v8Z0qU\ncrWKwq/ytxnnxZeHWBWbCRnMGs3CGPngxvvzIeHrcRRCFBjHEEfAKmWX2hI9ag5k\njh1/Vrt0LKq5JRTj9OmBDwwSbzwzBz28MFRRkw7X8wKBgQDm+BS3knjUtIwwgPrk\n2mYWrdzzVSbnI1LlVwuTPbH1vhY0ZOqXnMcZ7DOrnzPXJHdqCNK4HkXAcFMl1lpr\n34CCmeL5x7aPhYdA47kjI/Ku/GUt66rtMhbTPa2QSGM0ttOTPJE8O/cd+OiVF2AY\nDwFWKhdAjMzpaZQVk/eW5lSf/wKBgQDUVk1yocPXx0cfeZ3Kf8+bNyeKrDQQxbXx\naoCkG1QLn4CUxtMVQlRPJ/s7QTZXFHU2K3tijsMRs7n1+/T+crve5lWOwsK4rc5Y\nKkwhiVQ1s5OKSbCeyMD+zRfE0MoYPYq/m7y3Aux9M3vlzFZV7P1G97PE+V5di8Iz\nCenRduh92wKBgQC4rKlb8kicwlPJQIfbTmkMPy4IfhBiBZN1fnjp3Q2a0MdOU1Zj\nrV5g8sSJt/yqTCUS6kUaJFJfQTCqc07PK+DEDCk40J++9+QiVTKlz0tu8K4x3lpH\n16H6ezl4wPhZoMlg06/IuqWnGGtXMl6KPg0yiOsYmLwK8XkPG8V+qWIGnwKBgB7B\nxYQmRGz8E6ROhHmxm8va6GJg4UXQrbMjfzDGOJ1aZFCooCjDK90vaGfD4XApqXTI\nRZ4YGc91nikwbuNwkSAPczMzqOsBWhNRRSfScZ05vtRYKjpF0BbYdGnw6GUsfO2W\nTHah+MmF7Jtzxsm5g1KFTMfqay3XsbCc7f6GX9TjAoGBALi0S1ajbCdxrR//fBnp\nZ8J+YvO829rHOH2EBlGyqzaf2GjygZBAGBqQhepJiMftVNdXRFHUBB6gpYRUVyYd\nbrSPo+f11TBQZTkOL0/9A202WrtHTZsUKuPQXEwDixtkfDU/UDddofkON/yeUTUg\nMnkECTH6FT3EVQIUkaxp2yaJ\n-----END PRIVATE KEY-----\n',
    ],
    

];
