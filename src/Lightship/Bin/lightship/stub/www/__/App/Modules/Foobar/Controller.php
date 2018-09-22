<?php
namespace App\Modules\Foobar;

use Solarfield\Lightship\Events\DoTaskEvent;

class Controller extends \App\Controller {
	private function doLoad() {
		$model = $this->getModel();

		$model->set('requestId', $this->getEnvironment()->getVars()->get('requestId'));
	}

	public function onDoTask(DoTaskEvent $aEvt) {
		parent::onDoTask($aEvt);

		$hints = $this->getHints();

		if ($hints->get('doLoad')) {
			$this->doLoad();
		}
	}
}
