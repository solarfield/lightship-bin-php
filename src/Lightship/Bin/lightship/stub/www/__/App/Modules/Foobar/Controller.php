<?php
namespace App\Modules\Foobar;

class Controller extends \App\Controller {
	private function doLoad() {
		$model = $this->getModel();

		$model->set('requestId', $this->getEnvironment()->getVars()->get('requestId'));
	}

	public function doTask() {
		parent::doTask();

		$hints = $this->getHints();

		if ($hints->get('doLoad')) {
			$this->doLoad();
		}
	}
}
