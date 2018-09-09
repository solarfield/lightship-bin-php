<?php
require_once __DIR__ . '/../vendor/solarfield/lightship-php/src/Solarfield/Lightship/TerminalBootstrapper.php';

exit(\Solarfield\Lightship\TerminalBootstrapper::go([
	'projectPackageFilePath' => __DIR__ . '/..',
	'appPackageFilePath' => __DIR__,
]));
