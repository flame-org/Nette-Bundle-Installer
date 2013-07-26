<?php
/**
 * Class BaseInstaller
 *
 * @author: Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date: 26.07.13
 */
namespace Flame\Modules\Installers;

abstract class BaseInstaller implements IInstaller
{

	/**
	 * @param $config
	 * @param $class
	 * @param $name
	 * @return mixed
	 */
	protected function addExtension($config, $class, $name)
	{
		if(!isset($config['modules'])) {
			$config['modules'] = array();
		}

		if($name !== null) {
			if(!isset($config['modules'][$name])) {
				$config['modules'][$name] = $class;
			}
		}else{
			if(!in_array($class, $config['modules'])) {
				$config['modules'][] = $class;
			}
		}

		return $config;
	}

	/**
	 * @param $config
	 * @param $class
	 * @param $name
	 * @return mixed
	 */
	protected function removeExtension($config, $class, $name)
	{
		if(isset($config['modules'])) {
			if($name !== null) {
				if(isset($config['modules'][$name])) {
					unset($config['modules'][$name]);
				}
			}else{
				if($key = array_search($class, $config['modules'])) {
					unset($config['modules'][$key]);
				}
			}
		}

		return $config;
	}

} 