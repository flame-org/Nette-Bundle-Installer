<?php

namespace Flame\Bundles;

/**
 * Custom installer of Nette Budles
 */
class Installer extends \Composer\Installer\LibraryInstaller
{

	/** @var array */
	private static $supportedTypes = array(
		'nette-bundle',
	);

	/** @var string */
	private $appDir;

	public function __construct(\Composer\IO\IOInterface $io, \Composer\Composer $composer, $type = 'library')
	{
		parent::__construct($io, $composer, $type);

		$this->appDir = realpath(($this->vendorDir ? $this->vendorDir.'/' : '') . '../app');
	}

	/**
	 * @param $packageType
	 * @return bool
	 */
	public function supports($packageType)
	{
		return in_array($packageType, self::$supportedTypes);
	}

	/**
	 * @param \Composer\Package\PackageInterface $package
	 * @return string
	 * @throws \Exception
	 */
	public function getInstallPath(\Composer\Package\PackageInterface $package)
	{
		if($package->getType() == 'nette-bundle') {
			$targetDir = $package->getTargetDir();
			return $this->appDir . $this->getBundleName($package->getPrettyName()) . ($targetDir ? '/'.$targetDir : '');
		}else{
			throw new \Exception("Not recognized package type '{$package->getType()}'");
		}
	}

	/**
	 * @param $prettyName
	 * @return string
	 */
	private function getBundleName($prettyName)
	{

		if(strpos($prettyName, '/') !== false){
			$lastOne = strrchr($prettyName, '/');
			$name = str_replace('/', '', $lastOne);

			if(strpos($name, '-') !== false){
				$pieces = explode('-', $name);

				if(count($pieces)){
					$name = '';
					foreach($pieces as $piece){
						$name .= ucfirst($piece);
					}

					return $name;
				}
			}
		}

		return $prettyName;

	}
}
