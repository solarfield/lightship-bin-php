<?php
namespace App\Modules\Home;

class HtmlView extends \App\HtmlView {
	public function createMainContent() {
		$model = $this->getModel();

		ob_start();

		?>
		<article>
			<header>
				<h2>Home</h2>
			</header>

			<p>This is the Home module.</p>
		</article>
		<?php

		return ob_get_clean();
	}
}
