<?php
declare(strict_types=1);

namespace App;

use Solarfield\Lightship\Events\DoTaskEvent;
use Solarfield\Lightship\Events\ProcessRouteEvent;

class Controller extends \Solarfield\Lightship\WebController {
	protected function resolvePlugins() {
		parent::resolvePlugins();
		$this->getPlugins()->register('LightshipBridge');
	}

	protected function onProcessRoute(ProcessRouteEvent $aEvt) {
		parent::onProcessRoute($aEvt);

		$route = $aEvt->getContext()->getRoute();

		//if url is "/" (root)
		if ($route->getNextStep() == '') {
			$aEvt->getContext()->setRoute([
				'moduleCode' => 'Home',
			]);
		}

		if (
			preg_match('/^\\/foo\\/?$/i', (string)$route->getNextStep()) == 1
			|| preg_match('/^\\/bar\\/?$/i', (string)$route->getNextStep()) == 1
		) {
			$aEvt->getContext()->setRoute([
				'moduleCode' => 'Foobar',
			]);
		}
	}

	protected function onDoTask(DoTaskEvent $aEvt) {
		parent::onDoTask($aEvt);

		$this->getModel()->set('app.version', '1.0.0');
	}
}
