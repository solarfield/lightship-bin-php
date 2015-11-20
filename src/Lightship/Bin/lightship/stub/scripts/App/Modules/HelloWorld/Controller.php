<?php
namespace App\Modules\HelloWorld;

use App\Environment;

class Controller extends \App\Controller {
	protected function executeScript() {
		$stdout = Environment::getStandardOutput();
		$stdout->write("Hello World.");
	}
}
