<?php

return [
    //'oracle' => [
    //    'driver'         => 'oracle',
    //    'tns'            => env('DB_TNS', ''),
    //    'host'           => env('DB_HOST', 'localhost'),
    //    'port'           => env('DB_PORT', '1521'),
    //    'database'       => env('DB_DATABASE', 'xe'),
    //    'username'       => env('DB_USERNAME', 'root'),
    //    'password'       => env('DB_PASSWORD', ''),
    //    'charset'        => env('DB_CHARSET', 'AL32UTF8'),
    //    'prefix'         => env('DB_PREFIX', ''),
    //    'prefix_schema'  => env('DB_SCHEMA_PREFIX', ''),
    //    'edition'        => env('DB_EDITION', 'ora$base'),
    //    'server_version' => env('DB_SERVER_VERSION', '11g'),
    //],
	'oracle' => [
		'driver' => 'oracle',
		'host' => 'localhost',
		'port' => '1521',
		'database' => 'xe',
		'username' => 'root',
		'password' => '1234',
		'charset' => 'AL32UTF8',
		'prefix' => '',
	],
];
