<?php

namespace Flame\Modules;

use Composer\Composer;
use Composer\Installer\LibraryInstaller;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Flame\Utils\Reader;
use Flame\Utils\Writer;

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

	/**
	 * @param InstalledRepositoryInterface $repo
	 * @param PackageInterface $package
	 */
	public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
	{
		$this->extensionManager($package);
		parent::install($repo, $package);
	}

	/**
	 * @param InstalledRepositoryInterface $repo
	 * @param PackageInterface $package
	 */
	public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
	{
		$this->extensionManager($package, false);
		parent::uninstall($repo, $package);
	}

	/**
	 * @param PackageInterface $package
	 * @param bool $addFlag
	 */
	private function extensionManager(PackageInterface $package, $addFlag = true)
	{
		$extra = $this->getExtra($package);
		$class = (isset($extra['class'])) ? $extra['class'] : null;

		if($class !== null) {
			$name = (isset($extra['name'])) ? $extra['name'] : null;

			if(file_exists($phpFile = $this->appDir . $this->defaultConfigs[0])) {

				$config = Reader::parsePhp($phpFile);
				if($addFlag === true) {
					$config = $this->addExtension($config, $class, $name);
				}else{
					$config = $this->removeExtension($config, $class, $name);
				}

				Writer::dumpPhp($phpFile, $config);

			}elseif(file_exists($neonFile = $this->appDir . $this->defaultConfigs[1])) {

				$config = Reader::parseNeon($neonFile);

				if($addFlag === true) {
					$config = $this->addExtension($config, $class, $name);
				}else{
					$config = $this->removeExtension($config, $class, $name);
				}

				Writer::dumpNeon($neonFile, $config);
			}
		}
	}

	/**
	 * @param $config
	 * @param $class
	 * @param $name
	 * @return mixed
	 */
	private function addExtension($config, $class, $name)
	{
		if(!isset($config['modules'])) {
			$config['modules'] = array();
		}

		if($name !== null) {
			if(!isset($config['modules'][$name])) {
				$config['modules'][$name] = $class;
			}
		}else{
			if(!in_array($class, $config['modules'])) {
				$config['modules'][] = $class;
			}
		}

		return $config;
	}

	/**
	 * @param $config
	 * @param $class
	 * @param $name
	 * @return mixed
	 */
	private function removeExtension($config, $class, $name)
	{
		if(isset($config['modules'])) {
			if($name !== null) {
				if(isset($config['modules'][$name])) {
					unset($config['modules'][$name]);
				}
			}else{
				if($key = array_search($class, $config['modules'])) {
					unset($config['modules'][$key]);
				}
			}
		}

		return $config;
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
	 * @return array
	 */
	private function getExtra(PackageInterface $package)
	{
		$extra = $package->getExtra();
		return (isset($extra['module'])) ? $extra['module'] : array();
	}
}
