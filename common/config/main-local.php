<?php
//Имя пользователя: webadmin
//Пароль: sbncotJnfY

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=ec2-54-235-151-252.compute-1.amazonaws.com;dbname=d3m1jkh1saukl9',
            'username' => 'nvwwtvmauftsvy',
            'password' => 'T4Tl019smi9ETrJQHHc8GxxzPj',
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
