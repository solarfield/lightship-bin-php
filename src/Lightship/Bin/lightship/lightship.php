<?php
namespace Lightship\Bin;

use Exception;

class Bin {
	const OS_WINDOWS = 1;
	const OS_LINUX = 2;

	private $os;

	//imported args
	private $args = [];

	//'help' should always be the first item
	private $availableCommands = array('help', 'app', 'webdep', 'version');

	//retrieves an argument value by name
	//$aName includes any - or -- prefix
	private function arg($aName) {
		return array_key_exists($aName, $this->args) ? $this->args[$aName] : null;
	}

	//imports command line arguments
	//Currently only 3 forms are supported:
	//foo          Task style. e.g. help, app, version
	//--foo=bar    Single or double quotes around value are optional
	//--foo        Value will be true.
	private function importArgs() {
		$args = $_SERVER['argv'];
		array_shift($args);

		foreach ($args as $arg) {
			if (preg_match('/^(-{1,2}[^\\s=]+)(?:=[\'"]?(\\S+)[\'"]?)?$/', $arg, $matches) == 1) {
				//if --foo=bar
				if (count($matches) == 3) {
					$this->args[$matches[1]] = $matches[2];
				}

				//else --foo
				else {
					$this->args[$matches[1]] = true;
				}
			}

			else if (in_array($arg, $this->availableCommands)) {
				$this->args[$arg] = true;
			}

			else {
				throw new BinException(
					"Malformed terminal argument: '" . $arg . "'."
				);
			}
		}
	}

	/**
	 * @param string $aPath Path to directory.
	 * @param bool $aSilent If true, message will be not be output.
	 * @param array $aInfo Will be filled with message, etc.
	 * @return int 0 = created, 1 = already exists
	 * @throws BinException
	 */
	private function initDir($aPath, $aSilent = false, &$aInfo = array()) {
		if (file_exists($aPath)) {
			if (!is_dir($aPath)) {
				throw new BinException(
					"Expected directory at '" . realpath($aPath) . "'."
				);
			}

			$aInfo['msg'] = "OK      '" . realpath($aPath) . "' already exists.\n";
			if (!$aSilent) echo($aInfo['msg']);

			$result = 1;
		}

		else {
			try {
				mkdir($aPath);
			}
			catch (Exception $ex) {
				throw new BinException(
					"Could not create directory at '" . $aPath . "'."
						. "\nDetails: " . $ex->getMessage() . ".",
					0, $ex
				);
			}

			$aInfo['msg'] = "OK      '" . $aPath . "' created.\n";
			if (!$aSilent) echo($aInfo['msg']);

			$result = 0;
		}

		return $result;
	}

	/**
	 * @param string $aPath Path to file.
	 * @param null $aContents Optional contents for created file.
	 * @return int
	 *	0 = created
	 *	1 = already exists & contents match
	 *	2 = already exists & contents do not match
	 * @throws BinException
	 */
	private function initFile($aPath, $aContents = null) {
		if (file_exists($aPath)) {
			if (!is_file($aPath)) {
				throw new BinException(
					"Expected file at '" . realpath($aPath) . "'."
				);
			}

			if ((string)file_get_contents($aPath) === (string)$aContents) {
				echo("OK      '" . realpath($aPath) . "' already exists.\n");
				$result = 1;
			}

			else {
				echo("WARNING '" . realpath($aPath) . "' contents do not match. Check manually.\n");
				$result = 2;
			}
		}

		else {
			try {
				file_put_contents($aPath, $aContents);
			}
			catch (Exception $ex) {
				throw new BinException(
					"Could not create file at '" . $aPath . "'."
						. "\nDetails: " . $ex->getMessage() . ".",
					0, $ex
				);
			}

			echo("OK      '" . $aPath . "' created.\n");

			$result = 0;
		}

		return $result;
	}

	private function validateDepForWeb($aName) {
		$matches = [];

		if (preg_match('/^([^\\\\\\/]+)\\/([^\\\\\\/]+)$/', $aName, $matches) !== 1) {
			throw new BinException(
				"Invalid --name.",
				0, null, 'webdep'
			);
		}

		return [
			'vendor' => $matches[1],
			'package' => $matches[2],
		];
	}

	private function addWebDep($aProjectDir, $aName, &$aInfo = array()) {
		$parts = $this->validateDepForWeb($aName);

		if (!file_exists($aProjectDir . '/vendor/' . $aName)) {
			throw new BinException(
				"Dependency '$aName' was not found.",
				0, null, 'webdep'
			);
		}

		$webDepsDirPath = realpath($aProjectDir . '/www/__/deps');

		if ($webDepsDirPath === false) {
			throw new BinException(
				"Expected directory at ./www/__/deps",
				0, null, 'webdep'
			);
		}

		$webDepsVendorPath = $webDepsDirPath . DIRECTORY_SEPARATOR . $parts['vendor'];

		if (!file_exists($webDepsVendorPath)) {
			mkdir($webDepsVendorPath);
		}

		$linkTarget =
			'..'
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'vendor'
			. DIRECTORY_SEPARATOR . $parts['vendor']
			. DIRECTORY_SEPARATOR . $parts['package'];

		$linkPath = $webDepsVendorPath . DIRECTORY_SEPARATOR . $parts['package'];

		if (file_exists($linkPath)) {
			$aInfo['msg'] = "OK      Dependency '$aName' already exists in web front-end.\n";
		}

		else {
			//if a broken symlink exists
			if (is_link($linkPath) !== false) {
				$this->removeWebDep($aProjectDir, $aName);
			}

			//WORKAROUND: symlink() fails on windows due to https://bugs.php.net/bug.php?id=48975
			if ($this->os == self::OS_WINDOWS) shell_exec("mklink /D \"$linkPath\" \"$linkTarget\"");
			else symlink($linkTarget, $linkPath);

			$aInfo['msg'] = "OK      Dependency '$aName' added to web front-end.\n";
		}
	}

	private function removeWebDep($aProjectDir, $aName, &$aInfo = array()) {
		$actuallyRemoved = false;
		$parts = $this->validateDepForWeb($aName);

		$webDepsDirPath = realpath($aProjectDir . '/www/__/deps');

		if ($webDepsDirPath !== false) {
			$webDepsVendorPath = $webDepsDirPath . DIRECTORY_SEPARATOR . $parts['vendor'];

			if (file_exists($webDepsVendorPath)) {
				$linkPath = $webDepsVendorPath . DIRECTORY_SEPARATOR . $parts['package'];
				$isLink = is_link($linkPath);

				if (file_exists($linkPath) && !$isLink) throw new BinException(
					"Expected symbolic link at '$linkPath'.",
					0, null, 'webdep'
				);

				if (is_link($linkPath)) {
					if ($this->os == self::OS_WINDOWS) rmdir($linkPath);
					else unlink($linkPath);

					$actuallyRemoved = true;
				}
			}
		}

		if ($actuallyRemoved) {
			$aInfo['msg'] = "OK      Dependency '$aName' removed from web front-end.\n";
		}
		else {
			$aInfo['msg'] = "WARNING Dependency '$aName' does not exist in web front-end.\n";
		}
	}

	private function gohelp() {
		if ($this->arg('app')) {
			echo(file_get_contents('phar://lightship.phar/help/app.txt'));
		}

		else if ($this->arg('webdep')) {
			echo(file_get_contents('phar://lightship.phar/help/webdep.txt'));
		}

		else {
			echo(file_get_contents('phar://lightship.phar/help/default.txt'));
		}
	}

	private function goversion() {
		echo("LIGHTSHIP-BIN    0.0.1-alpha\n");
	}

	private function goapp() {
		$originalPkgDirPath = $this->arg('--path');
		$originalPkgDirPath = str_replace('\\', '/', $originalPkgDirPath);

		if ($originalPkgDirPath == '' || $originalPkgDirPath === true) {
			throw new BinException(
				"The --path option is required.",
				0, null, 'app'
			);
		}

		//if relative path
		if (!(preg_match('/^\\//', $originalPkgDirPath) == 1 || preg_match('/[a-z]:\\\/i', $originalPkgDirPath) == 1)) {
			//explicitly prepend the current working dir to workaround phar owning relative paths
			$pkgDirPath = getcwd() . '/' . $originalPkgDirPath;
		}
		else {
			$pkgDirPath = $originalPkgDirPath;
		}

		//init the project dir at --path
		$info = [];
		$result = $this->initDir($pkgDirPath, true, $info);
		echo("Creating app project at '" . $originalPkgDirPath . "'.\n");
		$pkgDirPath = realpath($pkgDirPath);

		if ($pkgDirPath != $originalPkgDirPath) {
			echo("--path '$originalPkgDirPath' resolves to '$pkgDirPath'.\n");
		}

		if ($result == 1) {
			if ($this->arg('--force') == false) {
				throw new BinException(
					"Directory '$pkgDirPath' already exists. Specify the --force option to override.",
					0, null, 'app'
				);
			}
		}
		echo($info['msg']);
		
		$this->initDir ($pkgDirPath . '/files');
		echo("NOTE    Check that web server user has write access to '$pkgDirPath/files' and descendants.\n");
		
		$this->initFile($pkgDirPath . '/files/.gitignore');
		
		$this->initDir ($pkgDirPath . '/infrastructure');
		$this->initDir ($pkgDirPath . '/infrastructure/apache');
		$this->initFile($pkgDirPath . '/infrastructure/apache/.gitignore', file_get_contents('phar://lightship.phar/stub/infrastructure/apache/.gitignore'));

		$contents = file_get_contents('phar://lightship.phar/stub/infrastructure/apache/virtual-host-defaults.example.conf');
		$contents = str_replace('$PROJECT_DIR', $pkgDirPath, $contents);
		$this->initFile($pkgDirPath . '/infrastructure/apache/virtual-host-defaults.example.conf', $contents);
		$this->initFile($pkgDirPath . '/infrastructure/apache/virtual-host-defaults.conf', $contents);

		$contents = file_get_contents('phar://lightship.phar/stub/infrastructure/apache/site.example.conf');
		$contents = str_replace('$PROJECT_DIR', $pkgDirPath, $contents);
		$this->initFile($pkgDirPath . '/infrastructure/apache/site.example.conf', $contents);
		$this->initFile($pkgDirPath . '/infrastructure/apache/site-dev.conf', $contents);

		$this->initDir ($pkgDirPath . '/scripts');
		$this->initFile($pkgDirPath . '/scripts/.gitignore', file_get_contents('phar://lightship.phar/stub/scripts/.gitignore'));
		$this->initFile($pkgDirPath . '/scripts/config.example.php', file_get_contents('phar://lightship.phar/stub/scripts/config.example.php'));
		$this->initFile($pkgDirPath . '/scripts/execute.php', file_get_contents('phar://lightship.phar/stub/scripts/execute.php'));
		$this->initDir ($pkgDirPath . '/scripts/App');
		$this->initFile($pkgDirPath . '/scripts/App/Environment.php', file_get_contents('phar://lightship.phar/stub/scripts/App/Environment.php'));
		$this->initFile($pkgDirPath . '/scripts/App/Controller.php', file_get_contents('phar://lightship.phar/stub/scripts/App/Controller.php'));
		$this->initDir ($pkgDirPath . '/scripts/App/Modules');
		$this->initDir ($pkgDirPath . '/scripts/App/Modules/HelloWorld');
		$this->initFile($pkgDirPath . '/scripts/App/Modules/HelloWorld/Controller.php', file_get_contents('phar://lightship.phar/stub/scripts/App/Modules/HelloWorld/Controller.php'));
		$this->initDir ($pkgDirPath . '/www');
		$this->initFile($pkgDirPath . '/www/index.php', file_get_contents('phar://lightship.phar/stub/www/index.php'));
		$this->initDir ($pkgDirPath . '/www/__');
		$this->initFile($pkgDirPath . '/www/__/.gitignore', file_get_contents('phar://lightship.phar/stub/www/__/.gitignore'));
		$this->initFile($pkgDirPath . '/www/__/config.example.php', file_get_contents('phar://lightship.phar/stub/www/__/config.example.php'));

		$this->initDir ($pkgDirPath . '/www/__/deps');
		$this->initFile($pkgDirPath . '/www/__/deps/.gitignore', file_get_contents('phar://lightship.phar/stub/www/__/deps/.gitignore'));

		$this->initDir ($pkgDirPath . '/www/__/App');
		$this->initFile($pkgDirPath . '/www/__/App/Environment.js', file_get_contents('phar://lightship.phar/stub/www/__/App/Environment.js'));
		$this->initFile($pkgDirPath . '/www/__/App/Environment.php', file_get_contents('phar://lightship.phar/stub/www/__/App/Environment.php'));
		$this->initFile($pkgDirPath . '/www/__/App/Controller.js', file_get_contents('phar://lightship.phar/stub/www/__/App/Controller.js'));
		$this->initFile($pkgDirPath . '/www/__/App/Controller.php', file_get_contents('phar://lightship.phar/stub/www/__/App/Controller.php'));
		$this->initFile($pkgDirPath . '/www/__/App/HtmlView.php', file_get_contents('phar://lightship.phar/stub/www/__/App/HtmlView.php'));
		$this->initFile($pkgDirPath . '/www/__/App/JsonView.php', file_get_contents('phar://lightship.phar/stub/www/__/App/JsonView.php'));
		$this->initDir ($pkgDirPath . '/www/__/App/style');
		$this->initFile($pkgDirPath . '/www/__/App/style/style.css');
		$this->initDir ($pkgDirPath . '/www/__/App/Modules');

		$this->initDir ($pkgDirPath . '/www/__/App/Modules/Home');
		$this->initDir ($pkgDirPath . '/www/__/App/Modules/Home/style');
		$this->initFile($pkgDirPath . '/www/__/App/Modules/Home/style/style.css');
		$this->initFile($pkgDirPath . '/www/__/App/Modules/Home/Controller.php', file_get_contents('phar://lightship.phar/stub/www/__/App/Modules/Home/Controller.php'));
		$this->initFile($pkgDirPath . '/www/__/App/Modules/Home/Controller.js', file_get_contents('phar://lightship.phar/stub/www/__/App/Modules/Home/Controller.js'));
		$this->initFile($pkgDirPath . '/www/__/App/Modules/Home/HtmlView.php', file_get_contents('phar://lightship.phar/stub/www/__/App/Modules/Home/HtmlView.php'));

		$this->initDir ($pkgDirPath . '/www/__/App/Modules/Foobar');
		$this->initFile($pkgDirPath . '/www/__/App/Modules/Foobar/Controller.php', file_get_contents('phar://lightship.phar/stub/www/__/App/Modules/Foobar/Controller.php'));
		$this->initFile($pkgDirPath . '/www/__/App/Modules/Foobar/Controller.js', file_get_contents('phar://lightship.phar/stub/www/__/App/Modules/Foobar/Controller.js'));
		$this->initFile($pkgDirPath . '/www/__/App/Modules/Foobar/HtmlView.php', file_get_contents('phar://lightship.phar/stub/www/__/App/Modules/Foobar/HtmlView.php'));

		$this->initDir ($pkgDirPath . '/www/__/App/Modules/Error');
		$this->initFile($pkgDirPath . '/www/__/App/Modules/Error/Controller.php', file_get_contents('phar://lightship.phar/stub/www/__/App/Modules/Error/Controller.php'));
		$this->initFile($pkgDirPath . '/www/__/App/Modules/Error/HtmlView.php', file_get_contents('phar://lightship.phar/stub/www/__/App/Modules/Error/HtmlView.php'));

		$this->initDir ($pkgDirPath . '/www/files');
		echo("NOTE    Check that web server user has write access to '$pkgDirPath/www/files' and descendants.\n");

		$this->initFile($pkgDirPath . '/www/files/.gitignore');

		$info = []; $this->addWebDep($pkgDirPath, 'solarfield/ok-kit-js', $info); echo($info['msg']);
		$info = []; $this->addWebDep($pkgDirPath, 'solarfield/lightship-js', $info); echo($info['msg']);
		$info = []; $this->addWebDep($pkgDirPath, 'systemjs/systemjs', $info); echo($info['msg']);
	}

	private function gowebdep() {
		if ($this->arg('--remove')) {
			$info = [];
			$this->removeWebDep(getcwd(), $this->arg('--name'), $info);
		}

		else {
			$info = [];
			$this->addWebDep(getcwd(), $this->arg('--name'), $info);
		}

		echo($info['msg']);
	}

	public function go() {
		set_error_handler(function ($num, $msg, $file, $line) {
			throw new \ErrorException($msg, 0, $num, $file, $line);
		});

		try {
			$this->importArgs();

			$command = null;

			foreach ($this->availableCommands as $c) {
				if ($this->arg($c)) {
					$command = $c;
					break;
				}
			}

			if ($command === null) {
				$command = 'help';
			}

			$command = 'go' . str_replace('-', '', $command);
			$this->$command();
			$result = 0;
		}

		catch (Exception $ex) {
			if ($ex instanceof BinException) {
				$helpCommand = $ex->getHelp();

				if ($helpCommand) {
					$helpText = ' ' . $helpCommand;
				}
				else {
					$helpText = '';
				}

				echo(
					"ERROR: " . $ex->getMessage()
					. "\nSee 'lightship help$helpText' for more information.\n"
				);

				$result = 1;
			}

			else {
				echo(
					"ERROR: An internal error occurred.\n"
					. "Please report an issue.\n"
					. "Details:\n"
					. $ex . "\n"
				);

				$result = 2;
			}
		}

		return $result;
	}

	public function __construct() {
		$this->os = stripos(PHP_OS, 'win') !== false ? self::OS_WINDOWS : self::OS_LINUX;
	}
}

class BinException extends Exception {
	private $help;

	public function getHelp() {
		return $this->help;
	}

	public function __construct($message = "", $code = 0, Exception $previous = null, $aHelp = null) {
		parent::__construct($message, $code, $previous);
		$this->help = $aHelp;
	}
}

return (new Bin())->go();
