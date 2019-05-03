<?php

$appSettings = include __DIR__.'/../src/settings.php';

return [
    'paths'        => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/migrations',
        'seeds'      => '%%PHINX_CONFIG_DIR%%/seeds',
    ],
    'migration_base_class' => '\Migrator\Migration',
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database'        => 'db',
        'db'             => [
            'adapter' => $appSettings['db']['driver'],
            'host'    => $appSettings['db']['host'],
            'name'    => $appSettings['db']['database'],
            'user'    => $appSettings['db']['username'],
            'pass'    => $appSettings['db']['password'],
            'port'    => $appSettings['db']['port'],
            'charset' => $appSettings['db']['charset'],
        ],
    ],
];
