<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
   /* 'defaultController'=>'photobooks',*/
    'defaultRoute' => 'intro/index',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl'=>array('user/login'),
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'user/error',
        ],

        'userurl' => [

            'class' => 'app\components\UserUrl',

        ],
        'alphaid' => [

            'class' => 'app\components\AlphaId',

        ],
        'request'=>[

            'class' => 'common\components\Request',

            'web'=> '/frontend/web'

        ],
    ],
    'params' => $params,
];
