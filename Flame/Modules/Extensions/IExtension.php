<?php
/**
 * Class IExtension
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 26.07.13
 */

namespace Flame\Modules\Extensions;


interface IExtension
{

	/**
	 * @return string
	 */
	public function getConfigFile();

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @return string
	 */
	public function getClassName();
} 