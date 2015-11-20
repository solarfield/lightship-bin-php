<?php
namespace App\Modules\Error;

class HtmlView extends \App\HtmlView {
	public function createMainContent() {
		parent::createMainContent();

		$model = $this->getModel();
		$errorInfo = $model->get('errorInfo');

		ob_start();

		?>
		<article>
			<h2>Oops!</h2>

			<p><?php $this->out($errorInfo['message']) ?></p>

			<?php
			if ($errorInfo['details']) {
				?><pre><?php $this->out($errorInfo['details']); ?></pre><?php
			}
			?>
		</article>
		<?php

		return ob_get_clean();
	}
}
