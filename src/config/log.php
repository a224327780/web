<?php
return [
    'traceLevel' => 0,
    'targets' => [
        [
            'class' => 'app\components\BaseFileTarget',
            'levels' => ['info', 'trace'],
            'categories' => ['yii\db\Command::query', 'yii\db\Command::execute'],
            'logPath' => 'sql',
        ],
        [
            'class' => 'app\components\BaseFileTarget',
            'levels' => ['info'],
            'logPath' => 'access',
            'logVars' => ['_GET', '_POST'],
            'except' => [
                'yii\web\Session:*',
                'yii\web\User:*',
                'yii\db\Command:*',
                'yii\db\Connection:*',
            ],
        ],
        [
            'class' => 'app\components\BaseFileTarget',
            'levels' => ['error'],
            'logPath' => 'error',
//            'logVars' => ['_GET', '_POST']
        ],
        [
            'class' => 'app\components\BaseFileTarget',
            'levels' => ['warning'],
            'logPath' => 'warning',
            'logVars' => ['_GET', '_POST'],
            //'categories' => ['app\extensions\payment\*'],
        ]
    ],
];