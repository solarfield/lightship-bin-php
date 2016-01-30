<?php
namespace App;

use Solarfield\Batten\Event;
use Solarfield\Batten\Reflector;
use Solarfield\Lightship\StringBufferEvent;

class HtmlView extends \Solarfield\Lightship\HtmlView {
	protected function resolveStyleIncludes() {
		parent::resolveStyleIncludes();

		$includes = $this->getStyleIncludes();

		$includes->addFile('/style/style.css', [
			'base' => 'app',
			'onlyIfExists' => true,
			'group' => 2000,
			'bundleKey' => 'app',
		]);

		$includes->addFile('/style/style.css', [
			'base' => 'module',
			'onlyIfExists' => true,
			'group' => 2000,
			'bundleKey' => 'module',
		]);

		if (Reflector::inSurfaceOrModuleMethodCall()) {
			$this->dispatchEvent(
				new Event('app-resolve-style-includes', ['target' => $this])
			);
		}
	}

	protected function resolveScriptIncludes() {
		parent::resolveScriptIncludes();

		$includes = $this->getScriptIncludes();
		$appWebPath = Environment::getVars()->get('appPackageWebPath');

		//TODO
		/*$includes->addFile($appWebPath . '/deps/foo/foo.js', [
			'bundleKey' => 'app',
		]);*/

		if (Reflector::inSurfaceOrModuleMethodCall()) {
			$this->dispatchEvent(
				new Event('app-resolve-script-includes', ['target' => $this])
			);
		}
	}

	public function createBodyContent() {
		$model = $this->getModel();

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
		$content = '';

		if (Reflector::inSurfaceOrModuleMethodCall()) {
			$this->dispatchEvent(
				new StringBufferEvent('app-create-main-content', ['target' => $this], $content)
			);
		}

		return $content;
	}
}
