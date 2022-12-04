<?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;port=3306;dbname=db',
    'username' => 'user',
    'password' => 'password',
    'charset' => 'utf8mb4',
    'tablePrefix' => 'ii_',
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 86400,
    'schemaCache' => 'cache_db_schema'
];