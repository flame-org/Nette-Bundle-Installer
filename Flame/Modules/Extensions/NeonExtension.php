<?php
/**
 * Class NeonExtension
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 26.07.13
 */
namespace Flame\Modules\Extensions;

use Flame\Modules\Installers\NeonInstaller;

class NeonExtension extends BaseExtension
{

	/** @var  string */
	private $appDir;

	/** @var  string */
	private $class;

	/** @var \Flame\Modules\Installers\NeonInstaller  */
	private $installer;

	/** @var  string */
	private $name;

	/**
	 * @param $appDir
	 * @param $class
	 * @param $name
	 */
	function __construct($appDir, $class, $name)
	{
		$this->appDir = (string) $appDir;
		$this->class = (string) $class;
		$this->name = (string) $name;

		$this->installer = new NeonInstaller();
	}

	/**
	 * @return string
	 */
	public function getConfigFile()
	{
		return $this->appDir . '/config/extensions.neon';
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getClassName()
	{
		return $this->class;
	}

	/**
	 * @return bool
	 */
	public function install()
	{
		return $this->installer->install($this);
	}

	/**
	 * @return bool
	 */
	public function uninstall()
	{
		return $this->installer->uninstall($this);
	}
}