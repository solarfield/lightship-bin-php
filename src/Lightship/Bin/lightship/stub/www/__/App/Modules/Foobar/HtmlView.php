<?php
namespace App\Modules\Foobar;

use Solarfield\Lightship\Events\ResolveHintsEvent;

class HtmlView extends \App\HtmlView {
	protected function onResolveHints(ResolveHintsEvent $aEvt) {
		parent::onResolveHints($aEvt);
		
		$hints = $this->getHints();
		$hints->set('doLoad', true);
	}

	public function createMainContent() {
		$model = $this->getModel();

		ob_start();

		?>
		<article>
			<header>
				<h2>Foobar</h2>
			</header>

			<p>Request ID: <?php $this->out($model->get('requestId')); ?></p>
		</article>
		<?php

		return ob_get_clean();
	}
}
