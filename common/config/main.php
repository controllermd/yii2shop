<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager'=>[//认证权限
            'class'=>\yii\rbac\DbManager::className(),
        ],
    ],
];
