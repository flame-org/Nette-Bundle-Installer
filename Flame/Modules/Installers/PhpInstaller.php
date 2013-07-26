<?php
/**
 * Class PhpInstaller
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 26.07.13
 */
namespace Flame\Modules\Installers;

use Flame\Modules\Extensions\IExtension;
use Flame\Modules\Parsers\PhpParser;
use Flame\Utils\PhpGenerator\Helpers;

class PhpInstaller extends BaseInstaller
{

	/** @var \Flame\Modules\Parsers\PhpParser  */
	private $parser;

	public function __construct()
	{
		$this->parser = new PhpParser();
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