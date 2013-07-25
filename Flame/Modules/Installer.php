<?php

namespace Flame\Modules;

use Composer\Composer;
use Composer\Installer\LibraryInstaller;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Flame\Utils\PhpGenerator\Helpers;

/**
 * Custom installer of Nette Modules
 */
class Installer extends LibraryInstaller
{
	/** @var string */
	private $appDir;

	/** @var array */
	private $supportedTypes = array(
		'nette-module',
	);

	/** @var array  */
	private $defaultConfigs = array(
		'/config/extensions.php',
		'/config/extensions.neon',
	);

	/**
	 * @param IOInterface $io
	 * @param Composer $composer
	 * @param string $type
	 */
	public function __construct(IOInterface $io, Composer $composer, $type = 'library')
	{
		parent::__construct($io, $composer, $type);

		$this->appDir = $this->getAppDirPath();
	}

	/**
	 * @param $packageType
	 * @return bool
	 */
	public function supports($packageType)
	{
		return in_array($packageType, $this->supportedTypes);
	}

	public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
	{
		$this->includeExtensionClass($package);
		parent::install($repo, $package);
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	private function getAppDirPath()
	{
		$path = realpath(($this->vendorDir ? $this->vendorDir.'/' : '') . '../app');
		if(!file_exists($path)) {
			$path = realpath(($this->vendorDir ? $this->vendorDir.'/' : '') . '../../app');
			if(!file_exists($path)) {
				throw new \Exception('We could not found "app" directory');
			}
		}

		return $path;
	}

	/**
	 * @param PackageInterface $package
	 */
	private function includeExtensionClass(PackageInterface $package)
	{
		$extra = $this->getExtra($package);
		if(isset($extra['class'])) {
			$class = $extra['class'];
			if(file_exists($extensionsFile = $this->appDir . $this->defaultConfigs[0])) {
				$config = include_once $extensionsFile;
				$config[] = $class;
				$fileContent = '<?php ' . PHP_EOL . 'return ' . Helpers::dump($config) . ';';
				file_put_contents($extensionsFile, $fileContent);
			}
		}
	}

	/**
	 * @param PackageInterface $package
	 * @return array
	 */
	private function getExtra(PackageInterface $package)
	{
		$extra = $package->getExtra();
		return (isset($extra['module'])) ? $extra['module'] : array();
	}
}
