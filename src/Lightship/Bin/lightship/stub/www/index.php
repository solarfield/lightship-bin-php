<?php
require_once __DIR__ . '/../vendor/solarfield/lightship-php/src/Solarfield/Lightship/Bootstrapper.php';

\Solarfield\Lightship\Bootstrapper::go([
	'projectPackageFilePath' => __DIR__ . '/..',
	'appPackageFilePath' => __DIR__ . '/__',
]);
