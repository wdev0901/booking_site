<?php


return [
    'delivery_keys' => [
        'mail.username' => 'email_from_address',
        'mail.password' => 'email_smtp_password',
        'mail.host' => 'email_smtp_host',
        'mail.driver' => 'email_driver',
        'mail.port' => 'email_port',
        'mail.encryption' => 'email_encryption_type',
        'mail.from.address' => 'email_from_address',
        'mail.from.name' => 'email_from_name',
        'services.mandrill.secret' => 'mandrill_api',
        'services.sparkpost.secret' => 'sparkpost_api',
        'services.mailgun.domain' => 'mailgun_domain',
        'services.mailgun.secret' => 'mailgun_api',
    ]
];