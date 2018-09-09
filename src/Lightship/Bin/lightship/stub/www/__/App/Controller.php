<?php
declare(strict_types=1);

namespace App;

class Controller extends \Solarfield\Lightship\WebController {
	protected function resolvePlugins() {
		parent::resolvePlugins();

		//TODO
	}

	protected function resolveOptions() {
		parent::resolveOptions();

		//TODO
	}

	public function processRoute($aInfo) {
		//if url is "/" (root)
		if ($aInfo['nextRoute'] == '') {
			return [
				'moduleCode' => 'Home',
			];
		}

		if (
			preg_match('/^\\/foo\\/?$/i', (string)$aInfo['nextRoute']) == 1
			|| preg_match('/^\\/bar\\/?$/i', (string)$aInfo['nextRoute']) == 1
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
	}
}
