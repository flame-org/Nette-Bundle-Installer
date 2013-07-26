<?php
/**
 * Class IParser
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 26.07.13
 */

namespace Flame\Modules\Parsers;

interface IParser
{

	/**
	 * @param string $file
	 * @return mixed
	 */
	public function read($file);

	/**
	 * @param string $file
	 * @param string $content
	 * @return bool
	 */
	public function write($file, $content);
} 