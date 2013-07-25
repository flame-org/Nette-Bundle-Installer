<?php
/**
 * Class Helpers
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 25.07.13
 */
namespace Flame\Utils\PhpGenerator;

/**
 * PHP code generator utils.
 *
 * @author     David Grudl
 */
class Helpers
{
	const PHP_IDENT = '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*';


	/**
	 * @param $var
	 * @return mixed|string
	 */
	public static function dump($var)
	{
		return self::_dump($var);
	}

	/**
	 * @param $var
	 * @param int $level
	 * @return mixed|string
	 * @throws \Exception
	 */
	private static function _dump(& $var, $level = 0)
	{
		if (is_float($var)) {
			$var = var_export($var, TRUE);
			return strpos($var, '.') === FALSE ? $var . '.0' : $var;

		} elseif (is_bool($var)) {
			return $var ? 'TRUE' : 'FALSE';

		} elseif (is_string($var) && (preg_match('#[^\x09\x20-\x7E\xA0-\x{10FFFF}]#u', $var) || preg_last_error())) {
			static $table;
			if ($table === NULL) {
				foreach (range("\x00", "\xFF") as $ch) {
					$table[$ch] = ord($ch) < 32 || ord($ch) >= 127
						? '\\x' . str_pad(dechex(ord($ch)), 2, '0', STR_PAD_LEFT)
						: $ch;
				}
				$table["\r"] = '\r';
				$table["\n"] = '\n';
				$table["\t"] = '\t';
				$table['$'] = '\\$';
				$table['\\'] = '\\\\';
				$table['"'] = '\\"';
			}
			return '"' . strtr($var, $table) . '"';

		} elseif (is_array($var)) {
			$s = '';
			$space = str_repeat("\t", $level);

			static $marker;
			if ($marker === NULL) {
				$marker = uniqid("\x00", TRUE);
			}
			if (empty($var)) {

			} elseif ($level > 50 || isset($var[$marker])) {
				throw new \Exception('Nesting level too deep or recursive dependency.');

			} else {
				$s .= "\n";
				$var[$marker] = TRUE;
				$counter = 0;
				foreach ($var as $k => & $v) {
					if ($k !== $marker) {
						$s .= "$space\t" . ($k === $counter ? '' : self::_dump($k) . " => ") . self::_dump($v, $level + 1) . ",\n";
						$counter = is_int($k) ? max($k + 1, $counter) : $counter;
					}
				}
				unset($var[$marker]);
				$s .= $space;
			}
			return "array($s)";

		} elseif (is_object($var)) {
			$arr = (array) $var;
			$s = '';
			$space = str_repeat("\t", $level);

			static $list = array();
			if (empty($arr)) {

			} elseif ($level > 50 || in_array($var, $list, TRUE)) {
				throw new \Exception('Nesting level too deep or recursive dependency.');

			} else {
				$s .= "\n";
				$list[] = $var;
				foreach ($arr as $k => & $v) {
					if ($k[0] === "\x00") {
						$k = substr($k, strrpos($k, "\x00") + 1);
					}
					$s .= "$space\t" . self::_dump($k) . " => " . self::_dump($v, $level + 1) . ",\n";
				}
				array_pop($list);
				$s .= $space;
			}
			return get_class($var) === 'stdClass'
				? "(object) array($s)"
				: __CLASS__ . "::createObject('" . get_class($var) . "', array($s))";

		} else {
			return var_export($var, TRUE);
		}
	}


	/**
	 * @param $statement
	 * @return mixed
	 */
	public static function format($statement)
	{
		$args = func_get_args();
		return self::formatArgs(array_shift($args), $args);
	}


	/**
	 * @param $statement
	 * @param array $args
	 * @return mixed
	 * @throws \Exception
	 */
	public static function formatArgs($statement, array $args)
	{
		$a = strpos($statement, '?');
		while ($a !== FALSE) {
			if (!$args) {
				throw new \Exception('Insufficient number of arguments.');
			}
			$arg = array_shift($args);
			if (substr($statement, $a + 1, 1) === '*') { // ?*
				if (!is_array($arg)) {
					throw new \Exception('Argument must be an array.');
				}
				$arg = implode(', ', array_map(array(__CLASS__, 'dump'), $arg));
				$statement = substr_replace($statement, $arg, $a, 2);

			} else {
				$arg = substr($statement, $a - 1, 1) === '$' || in_array(substr($statement, $a - 2, 2), array('->', '::'))
					? self::formatMember($arg) : self::_dump($arg);
				$statement = substr_replace($statement, $arg, $a, 1);
			}
			$a = strpos($statement, '?', $a + strlen($arg));
		}
		return $statement;
	}


	/**
	 * @param $name
	 * @return string
	 */
	public static function formatMember($name)
	{
		return !self::isIdentifier($name)
			? '{' . self::_dump($name) . '}'
			: $name ;
	}


	/**
	 * @param $value
	 * @return bool
	 */
	public static function isIdentifier($value)
	{
		return is_string($value) && preg_match('#^' . self::PHP_IDENT . '\z#', $value);
	}


	/**
	 * @param $class
	 * @param array $props
	 * @return mixed
	 */
	public static function createObject($class, array $props)
	{
		return unserialize('O' . substr(serialize((string) $class), 1, -1) . substr(serialize($props), 1));
	}

}