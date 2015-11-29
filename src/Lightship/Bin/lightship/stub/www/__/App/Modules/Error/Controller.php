<?php
namespace App\Modules\Error;

use Solarfield\Batten\UnresolvedRouteException;
use Solarfield\Lightship\HttpExceptionInterface;
use Solarfield\Lightship\UserFriendlyException;
use Solarfield\Lightship\UserFriendlyExceptionInterface;
use Solarfield\Ok\HttpUtils;

class Controller extends \App\Controller {
	public function processRoute($aInfo) {
		return [
			'moduleCode' => $this->getCode(),
		];
	}

	public function doTask() {
		parent::doTask();

		$model = $this->getModel();

		$errorInfo = [
			'message' => null,
			'details' => null,
			'httpStatus' => null,
		];

		//get the exception hinted from ::handleException()
		$originalError = $this->getHints()->get('app.errorState.error');

		if ($originalError) {
			if (\App\DEBUG) {
				$errorInfo['details'] = (string)$originalError;
			}


			//resolve the message

			if ($originalError instanceof UserFriendlyException) {
				/** @var UserFriendlyExceptionInterface $originalError */
				$errorInfo['message'] = $originalError->getUserFriendlyMessage();
			}

			else if ($originalError instanceof UnresolvedRouteException) {
				$errorInfo['message'] = "The resource could not be found.";
			}


			//resolve the https status

			if ($originalError instanceof HttpExceptionInterface) {
				/** @var HttpExceptionInterface $originalError */
				$errorInfo['httpStatus'] = $originalError->getHttpStatusCode();
			}

			else if ($originalError instanceof UnresolvedRouteException) {
				$errorInfo['httpStatus'] = 404;
			}
		}

		if (!$errorInfo['message']) {
			$errorInfo['message'] = 'An error has occurred.';
		}

		if (!$errorInfo['httpStatus']) {
			$errorInfo['httpStatus'] = 500;
		}

		$model->set('errorInfo', $errorInfo);
		header(HttpUtils::createStatusHeader($errorInfo['httpStatus']));
	}
}
