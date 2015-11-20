<?php
namespace App;

//define the absolute file path to the app dependencies directory
define('App\DEPENDENCIES_FILE_PATH', realpath(__DIR__ . '/../deps'));

//include the config
/** @noinspection PhpIncludeInspection */
$config = file_exists(__DIR__ . '/config.php') ? require_once __DIR__ . '/config.php' : null;

//create the environment
require_once __DIR__ . '/App/Environment.php';
Environment::init([
	'projectPackageFilePath' => __DIR__ . '/..',
	'appPackageFilePath' => __DIR__,
	'composerVendorFilePath' => __DIR__ . '/../vendor',
	'config' => $config,
]);

unset($config);

//boot the controller
require_once __DIR__ . '/App/Controller.php';
Controller::boot();
