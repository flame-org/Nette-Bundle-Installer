<?php
/**
 * Class NeonInstaller
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 26.07.13
 */
namespace Flame\Modules\Installers;

use Flame\Modules\Extensions\IExtension;
use Flame\Modules\Parsers\NeonParser;
use Flame\Utils\Neon;

class NeonInstaller extends BaseInstaller
{

	/** @var  NeonParser */
	private $parser;

	public function __construct()
	{
		$this->parser = new NeonParser();
	}

	/**
	 * @param IExtension $extension
	 * @return bool
	 */
	public function install(IExtension $extension)
	{
		$config = $this->parser->read($extension->getConfigFile());
		$config = $this->addExtension($config, $extension->getClassName(), $extension->getName());
		return $this->parser->write($extension->getConfigFile(), $config);
	}

	/**
	 * @param IExtension $extension
	 * @return bool
	 */
	public function uninstall(IExtension $extension)
	{
		$config = $this->parser->read($extension->getConfigFile());
		$config = $this->removeExtension($config, $extension->getClassName(), $extension->getName());
		return $this->parser->write($extension->getConfigFile(), $config);
	}
}