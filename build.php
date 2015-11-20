<?php
set_error_handler(function ($num, $msg, $file, $line) {
	throw new Exception($msg, 0, $num, $file, $line);
});

$targetDirPath = __DIR__ . '/target';

if (!file_exists($targetDirPath)) {
	mkdir($targetDirPath);
}

$phar = new Phar(
	$targetDirPath . '/lightship.phar',
	0, 'lightship.phar'
);

$phar->buildFromDirectory(__DIR__ . '/src/Lightship/Bin/lightship/');
$phar->setDefaultStub('lightship.php');
