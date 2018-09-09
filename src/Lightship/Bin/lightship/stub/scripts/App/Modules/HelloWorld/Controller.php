<?php
namespace App\Modules\HelloWorld;

class Controller extends \App\Controller {
	protected function executeScript() {
		$stdout = $this->getEnvironment()->getStandardOutput();
		$stdout->write("Hello World.");
	}
}
