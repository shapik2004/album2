<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'image' => array(
            'class' => 'yii\image\ImageDriver',
            'driver' => 'GD',  //GD or Imagick
        ),
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@frontend/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'passpartu2015',
                'password' => 'Test12345678',
                'port' => '465',
                'encryption' => 'ssl',
            ],
        ],
        'resourceManager' => [
            'class' => 'common\components\AmazonS3ResourceManager',
            'key' => 'AKIAIG4IXDZN74OWIHFQ',
            'secret' => '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',
            'bucket' => 'photobook-new'
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'module/<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',

            ]
        ],

    ],

    'language' => 'ru-RU', // <- here!

];
