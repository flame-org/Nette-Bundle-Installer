<?php
/**
 * Class Reader
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 25.07.13
 */
namespace Flame\Utils;

class Reader
{

	/**
	 * @param $path
	 * @return mixed
	 */
	public static function parsePhp($path)
	{
		return include $path;
	}

	/**
	 * @param $path
	 * @return mixed
	 */
	public static function parseNeon($path)
	{
		return Neon::decode(file_get_contents($path));
	}
} 