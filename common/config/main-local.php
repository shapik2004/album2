<?php
//Имя пользователя: webadmin
//Пароль: sbncotJnfY

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=localhost;dbname=photobook',
            'username' => 'postgres',
            'password' => 'Max31960',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
/*
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=postgres28383-photobook.jelastic.neohost.net dbname=photobook user=webadmin password=sbncotJnfY',
            'username' => 'webadmin',
            'password' => 'sbncotJnfY',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];*/
