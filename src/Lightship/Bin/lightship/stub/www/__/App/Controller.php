<?php
namespace App;

use Batten\Event;
use Batten\Reflector;

class Controller extends \Lightship\WebController {
	protected function resolvePlugins() {
		parent::resolvePlugins();

		//TODO
	}

	protected function resolveOptions() {
		parent::resolveOptions();

		//TODO

		if (Reflector::inSurfaceOrModuleMethodCall()) {
			$this->dispatchEvent(
				new Event('app-resolve-options', ['target' => $this])
			);
		}
	}

	public function processRoute($aInfo) {
		//if url is "/" (root)
		if ($aInfo['nextRoute'] == '') {
			return [
				'moduleCode' => 'Home',
			];
		}

		if (
			preg_match('/^\\/foo\\/?$/i', $aInfo['nextRoute']) == 1
			|| preg_match('/^\\/bar\\/?$/i', $aInfo['nextRoute']) == 1
		) {
			return [
				'moduleCode' => 'Foobar',
			];
		}

		return parent::processRoute($aInfo);
	}

	public function doTask() {
		parent::doTask();

		$input = $this->getInput();

		//TODO

		if (Reflector::inSurfaceOrModuleMethodCall()) {
			$this->dispatchEvent(
				new Event('app-do-task', ['target' => $this])
			);
		}
	}
}
