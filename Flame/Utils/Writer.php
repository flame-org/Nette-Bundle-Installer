<?php
/**
 * Class Writer
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 25.07.13
 */
namespace Flame\Utils;

use Flame\Utils\PhpGenerator\Helpers;

class Writer
{

	/**
	 * @param $filePath
	 * @param array $config
	 * @return int
	 */
	public static function dumpPhp($filePath, array $config)
	{
		$fileContent = '<?php ' . PHP_EOL . PHP_EOL . 'return ' . Helpers::dump($config) . ';';
		return file_put_contents($filePath, $fileContent);
	}

	/**
	 * @param $filePath
	 * @param array $config
	 * @return int
	 */
	public static function dumpNeon($filePath, array $config)
	{
		$config = Neon::encode($config);
		return file_put_contents($filePath, $config);
	}

} 