<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'language'=>'zh-CN',
    'defaultRoute' => 'brand/index',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'loginUrl'=>['user/login'],//配置默认登录界面
            'identityClass' => 'backend\models\User',
            'enableAutoLogin' => true,//自动登录
            'authTimeout'=>3600*7*24,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,

            'rules' => [
            ],
        ],
        'qiniu'=>[
            'class'=>\backend\components\Qiniu::className(),
            'up_host'=>'http://up-z2.qiniu.com',
            'accessKey'=>'lx0qNrgv00Im5b4AWC8Q4ahNtdpWV0qxWs4QXYpO',
            'secretKey'=>'iXZKSJjyFRsS30NAULviOQSP90y6LCWRs7-C638o',
            'bucket'=>'yii2',
            'domain'=>'http://or9r79bc4.bkt.clouddn.com/'
        ]
    ],
    'params' => $params,
];
