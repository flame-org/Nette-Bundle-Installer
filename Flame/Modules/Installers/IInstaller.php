<?php
/**
 * Class IInstaller
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 26.07.13
 */
namespace Flame\Modules\Installers;

use Flame\Modules\Extensions\IExtension;

interface IInstaller
{

	/**
	 * @param IExtension $extension
	 * @return bool
	 */
	public function install(IExtension $extension);

	/**
	 * @param IExtension $extension
	 * @return bool
	 */
	public function uninstall(IExtension $extension);
}