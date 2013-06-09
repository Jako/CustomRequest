<?php

/**
 * CustomRequest
 *
 * Copyright 2013 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * CustomRequest is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * CustomRequest is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * CustomRequest; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package customrequest
 * @subpackage classfile
 *
 * @author      Thomas Jakobi (thomas.jakobi@partout.info)
 * @copyright   Copyright 2013, Thomas Jakobi
 * @version     1.0
 */
class CustomRequest {

	/**
	 * A reference to the modX instance
	 * @var modX $modx
	 */
	public $modx;

	/**
	 * A configuration array
	 * @var array $config
	 */
	public $config;

	/**
	 * A setting array
	 * @var array $setting
	 */
	public $requests;

	/**
	 * The found resource id
	 * @var int $resourceId
	 */
	public $resourceId;

	/**
	 * The found alias
	 * @var int $alias
	 */
	private $alias;

	/**
	 * The found url params
	 * @var int $resourceId
	 */
	private $urlParams;

	/**
	 * The found regular expression to parse the url
	 * @var int $alias
	 */
	private $regEx;

	/**
	 * CustomRequest constructor
	 *
	 * @param modX &$modx A reference to the modX instance.
	 * @param array $config An array of configuration options. Optional.
	 */
	function __construct(modX &$modx, array $config = array()) {
		$this->modx = & $modx;

		$corePath = $this->modx->getOption('customrequest.core_path', NULL, MODX_CORE_PATH . 'components/customrequest/');

		/* loads some default paths for easier management */
		$this->config = array_merge(array(
			'corePath' => $corePath,
			'modelPath' => $corePath . 'model/',
			'pluginsPath' => $corePath . 'elements/plugins/',
			'configsPath' => $this->modx->getOption('customrequest.configsPath', NULL, $corePath . 'configs/'),
			'debug' => $this->modx->getOption('customrequest.debug', NULL, FALSE),
				), $config);
		$this->requests = $this->modx->fromJson($this->config['aliases']);
	}

	/**
	 * Load all config files and prepare the values.
	 *
	 * @access public
	 * @return void
	 */
	public function initialize() {
		// TODO: Caching of these calculated values.
		$configFiles = glob($this->config['configsPath'] . '*.config.inc.php');
		// import config files
		foreach ($configFiles as $configFile) {
			// $settings will be defined in each config file
			$settings = array();
			include $configFile;
			foreach ($settings as $setting) {
				// fill urlParams if defined
				$urlParams = (isset($setting['urlParams']) && is_array($setting['urlParams'])) ? $setting['urlParams'] : array();
				$regEx = (isset($setting['regEx']) && is_array($setting['regEx'])) ? $setting['regEx'] : FALSE;
				if (isset($setting['alias'])) {
					// if alias is defined, calculate the other values
					if (isset($setting['resourceId'])) {
						$resourceId = $setting['resourceId'];
					} elseif ($res = $this->modx->getObject('modResource', array('uri' => $setting['alias']))) {
						$resourceId = $res->get('id');
					} else {
						// if resourceId could not be calculated, don't use that setting
						if ($this->config['debug']) {
							$modx->log(modX::LOG_LEVEL_INFO, 'CustomRequest Plugin: Could not calculate the resourceId for the given alias');
						}
						break;
					}
					$alias = $setting['alias'];
				} elseif (isset($setting['resourceId'])) {
					// else if resourceId is defined, calculate the other values
					$resourceId = $setting['resourceId'];
					if (isset($setting['alias'])) {
						$alias = $setting['alias'];
					} elseif ($url = $this->modx->makeUrl($setting['resourceId'])) {
						$alias = $url;
					} else {
						// if alias could not be calculated, don't use that setting
						if ($this->config['debug']) {
							$modx->log(modX::LOG_LEVEL_INFO, 'CustomRequest Plugin: Could not calculate the alias for the given resourceId');
						}
						break;
					}
				}
				$this->requests[$alias] = array(
					'resourceId' => $resourceId,
					'alias' => $alias,
					'urlParams' => $urlParams,
					'regEx' => $regEx
				);
			}
		}
		return;
	}

	/**
	 * Check if the search string starts with one of the allowed aliases and
	 * prepare the url param string if successful.
	 *
	 * @access public
	 * @return boolean
	 */
	public function searchAliases($search) {
		$valid = FALSE;
		// loop through the allowed aliases
		foreach ($this->requests as $request) {
			// check if searched string starts with the alias
			if (0 === strpos($search, $request['alias'])) {
				// strip alias from seached string
				$this->urlParams = substr($search, strlen($request['alias']));
				// set the found resource id
				$this->resourceId = $request['resourceId'];
				// set the found alias
				$this->alias = $request['alias'];
				// and set the found regEx
				$this->regEx = $request['regEx'];
				$valid = TRUE;
				break;
			}
		}
		return $valid;
	}

	/**
	 * Prepare the request parameters.
	 *
	 * @access public
	 * @return void
	 */
	public function setRequest() {
		$params = str_replace('.html', '', $this->urlParams);
		if ($this->regEx) {
			$params = preg_match($this->regEx, $params);
		} else {
			$params = explode('/', $params);
		}
		if (count($params) >= 1) {
			$setting = $this->requests[$this->alias];
			// set the request parameters
			foreach ($params as $key => $value) {
				if (isset($setting['urlParams'][$key])) {
					$_REQUEST[$setting['urlParams'][$key]] = $value;
				} else {
					$_REQUEST['p' . ($key + 1)] = $value;
				}
			}
		}
		$this->modx->sendForward($this->resourceId);
		return;
	}

}

?>
