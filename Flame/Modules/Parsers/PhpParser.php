<?php
/**
 * Class PhpParser
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 26.07.13
 */
namespace Flame\Modules\Parsers;

use Flame\Utils\PhpGenerator\Helpers;

class PhpParser implements IParser
{

	/**
	 * @param string $file
	 * @return mixed
	 */
	public function read($file)
	{
		return include $file;
	}

	/**
	 * @param string $file
	 * @param string $content
	 * @return bool
	 */
	public function write($file, $content)
	{
		$fileContent = '<?php ' . PHP_EOL . PHP_EOL . 'return ' . Helpers::dump($content) . ';';
		return file_put_contents($file, $fileContent);
	}
}