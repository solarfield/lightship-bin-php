<?php
namespace App\Modules\Foobar;

use App\Environment;

class Controller extends \App\Controller {
	private function doLoad() {
		$model = $this->getModel();

		$model->set('requestId', Environment::getVars()->get('requestId'));
	}

	public function doTask() {
		parent::doTask();

		$hints = $this->getHints();

		if ($hints->get('doLoad')) {
			$this->doLoad();
		}
	}
}
