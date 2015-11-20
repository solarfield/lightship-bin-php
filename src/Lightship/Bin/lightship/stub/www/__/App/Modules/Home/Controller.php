<?php
namespace App\Modules\Home;

class Controller extends \App\Controller {
	private function doSomething() {
		//TODO
	}

	public function doTask() {
		parent::doTask();

		$input = $this->getInput();

		if ($input->getAsString('doSomething') == true) {
			$this->doSomething();
		}
	}
}
