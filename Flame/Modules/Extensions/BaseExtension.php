<?php
/**
 * Class BaseExtension
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 28.07.13
 */
namespace Flame\Modules\Extensions;

abstract class BaseExtension implements IExtension
{

	/**
	 * @return bool
	 */
	public function existConfigFile()
	{
		return file_exists($this->getConfigFile());
	}

}