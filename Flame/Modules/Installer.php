<?php

namespace Flame\Modules;

use Composer\Composer;
use Composer\Installer\LibraryInstaller;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Flame\Modules\Extensions\NeonExtension;
use Flame\Modules\Extensions\PhpExtension;

/**
 * Custom installer of Nette Modules
 */
class Installer extends LibraryInstaller
{
	/** @var string */
	private $appDir;

	/** @var array */
	private $supportedTypes = array('nette-module');

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
		$this->installExtension($package);
		parent::install($repo, $package);
	}

	/**
	 * @param InstalledRepositoryInterface $repo
	 * @param PackageInterface $package
	 */
	public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
	{
		$this->uninstallExtension($package);
		parent::uninstall($repo, $package);
	}

	/**
	 * @param PackageInterface $package
	 */
	private function installExtension(PackageInterface $package)
	{
		$extra = $this->getExtra($package);

		if(isset($extra['class'])) {
			$name = (isset($extra['name'])) ? $extra['name'] : null;

			$phpExtension = new PhpExtension($this->appDir, $extra['class'], $name);
			$phpExtension->install();

			$neonExtension = new NeonExtension($this->appDir, $extra['class'], $name);
			$neonExtension->install();
		}
	}

	/**
	 * @param PackageInterface $package
	 * @return bool
	 */
	private function uninstallExtension(PackageInterface $package)
	{
		$extra = $this->getExtra($package);

		if(isset($extra['class'])) {
			$name = (isset($extra['name'])) ? $extra['name'] : null;

			$phpExtension = new PhpExtension($this->appDir, $extra['class'], $name);
			$phpExtension->uninstall();

			$neonExtension = new NeonExtension($this->appDir, $extra['class'], $name);
			$neonExtension->uninstall();
		}
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
