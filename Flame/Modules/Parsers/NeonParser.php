<?php
/**
 * Class NeonParser
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 26.07.13
 */
namespace Flame\Modules\Parsers;

use Flame\Utils\Neon;

class NeonParser implements IParser
{

	/**
	 * @param string $file
	 * @return mixed
	 */
	public function read($file)
	{
		return Neon::decode(file_get_contents($file));
	}

	/**
	 * @param string $file
	 * @param string $content
	 * @return bool
	 */
	public function write($file, $content)
	{
		return file_put_contents($file, Neon::encode($content));
	}

} 