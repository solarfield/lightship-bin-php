<?php
namespace App\Modules\Home;

use Solarfield\Lightship\Events\DoTaskEvent;

class Controller extends \App\Controller {
	private function doSomething() {
		//TODO
	}
	
	public function onDoTask(DoTaskEvent $aEvt) {
		parent::onDoTask($aEvt);
		
		$input = $this->getInput();
		
		if ($input->getAsString('doSomething') == true) {
			$this->doSomething();
		}
	}
}
