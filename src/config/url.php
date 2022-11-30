<?php
return [
    'enablePrettyUrl' => TRUE,
    'showScriptName' => FALSE,
//    'suffix' => '.html',
    'rules' => [
        'admin' => 'admin/',
        '<page_alias:(about|job|contact)>' => 'page/index',
        ['suffix' => '.html', 'pattern' => '<controller:\w+>/<id:\d+>', 'route' => '<controller>/detail'],
        ['suffix' => '/', 'pattern' => '<controller:\w+>/<meta_id:\d+>', 'route' => '<controller>/'],
//        '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
//        '<controller:\w+>/<action:\w+>/' => '<controller>/<action>/',
        '<controller:\w+>/' => '<controller>/',
    ]
];