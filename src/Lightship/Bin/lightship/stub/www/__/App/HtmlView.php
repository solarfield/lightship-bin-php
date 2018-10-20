<?php
namespace App;

use Solarfield\Lightship\Events\ResolveStyleIncludesEvent;

class HtmlView extends \Solarfield\Lightship\HtmlView {
	protected function onResolveStyleIncludes(ResolveStyleIncludesEvent $aEvt) {
		parent::onResolveStyleIncludes($aEvt);

		$includes = $this->getStyleIncludes();

		$includes->addFile('/style/style.css', [
			'base' => 'app',
			'onlyIfExists' => true,
			'group' => 2000,
		]);

		$includes->addFile('/style/style.css', [
			'base' => 'module',
			'onlyIfExists' => true,
			'group' => 2000,
		]);
	}

	public function createBodyContent() {
		ob_start();

		?>
		<header class="appHeader">
			<h1 class="appHeading">
				<a href="/">My App</a>
			</h1>
		</header>

		<div class="appBody">
			<?php
			$html = trim($this->createMainContent());
			if ($html != '') {
				?>
				<div class="mainArea"><?php echo($this->createMainContent()); ?></div>
				<?php
			}
			unset($html);
			?>

			<ul>
				<li><a href="/foo">Foo</a></li>
				<li><a href="/bar">Bar</a></li>
				<li><a href="/asdf">Doesn't Exist</a></li>
			</ul>
		</div>
		<?php

		return ob_get_clean();
	}

	public function createMainContent() {
		return null;
	}
}
