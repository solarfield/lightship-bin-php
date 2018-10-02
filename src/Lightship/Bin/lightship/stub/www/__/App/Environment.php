<?php
namespace App;

class Environment extends \Solarfield\Lightship\WebEnvironment {
	protected function createComponentChain(): \Solarfield\Lightship\ComponentChain {
		$chain = parent::createComponentChain();

		// add solarfield/lightship-bridge-php to the component chain
		if (file_exists(($path = $this->getVars()->get('appDependenciesFilePath') . '/solarfield/lightship-bridge-php/src/Solarfield/LightshipBridge'))) {
			$chain->insertBefore('app', [
				'id' => 'solarfield/lightship-bridge',
				'namespace' => 'Solarfield\\LightshipBridge',
				'path' => $path,
			]);
		}

		return $chain;
	}
}
