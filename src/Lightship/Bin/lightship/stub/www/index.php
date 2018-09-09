<?php
require_once __DIR__ . '/../vendor/solarfield/lightship-php/src/Solarfield/Lightship/WebBootstrapper.php';

\Solarfield\Lightship\WebBootstrapper::go([
	'projectPackageFilePath' => __DIR__ . '/..',
	'appPackageFilePath' => __DIR__ . '/__',
]);
