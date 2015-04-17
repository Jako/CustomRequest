<?php

/**
 * CustomRequest
 *
 * Copyright 2013-2015 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package customrequest
 * @subpackage classfile
 */
class CustomRequest
{
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx;

    /**
     * The namespace
     * @var string $namespace
     */
    public $namespace = 'customrequest';

    /**
     * The class options
     * @var array $options
     */
    public $options = array();

    /**
     * The requests array
     * @var array requests
     */
    public $requests = array();

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
     * @var int $urlParams
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
     * @param modX $modx A reference to the modX instance.
     * @param array $options An array of options. Optional.
     */
    function __construct(modX &$modx, array $options = array())
    {
        $this->modx = &$modx;

        $corePath = $this->getOption('core_path', $options, $this->modx->getOption('core_path') . 'components/customrequest/');
        $assetsPath = $this->getOption('assets_path', $options, $this->modx->getOption('assets_path') . 'components/customrequest/');
        $assetsUrl = $this->getOption('assets_url', $options, $this->modx->getOption('assets_url') . 'components/customrequest/');

        // Load some default paths for easier management
        $this->options = array_merge(array(
            'namespace' => $this->namespace,
            'assetsPath' => $assetsPath,
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'imagesUrl' => $assetsUrl . 'images/',
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'pagesPath' => $corePath . 'elements/pages/',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'pluginsPath' => $corePath . 'elements/plugins/',
            'processorsPath' => $corePath . 'processors/',
            'templatesPath' => $corePath . 'templates/',
            'configsPath' => $this->getOption('configsPath', null, $corePath . 'configs/', true),
            'cachePath' => $this->modx->getOption('core_path') . 'cache/',
            'connectorUrl' => $assetsUrl . 'connector.php'
        ), $options);

        // Load (system) properties
        $this->options = array_merge($this->options, array(
            'debug' => $this->getOption('debug', null, false)
        ));

        $this->modx->addPackage('customrequest', $this->getOption('modelPath'));
        $this->modx->lexicon->load('customrequest:default');

        if (isset($this->options['aliases'])) {
            $this->requests = $this->modx->fromJson($this->options['aliases'], true);
        }
        if (!$this->requests) {
            $this->requests = array();
        }

        // Import old config files if no configuration is set
        if (!$this->modx->getCount('CustomrequestConfigs')) {
            $i = 0;
            $configFiles = glob($this->getOption('configsPath') . '*.config.inc.php');
            // Import old config files
            foreach ($configFiles as $configFile) {
                // $settings will be defined in each config file
                $settings = array();
                include $configFile;
                foreach ($settings as $key => $setting) {
                    // fill urlParams if defined
                    $urlParams = (isset($setting['urlParams']) && is_array($setting['urlParams'])) ? $setting['urlParams'] : array();
                    $regEx = (isset($setting['regEx'])) ? $setting['regEx'] : '';
                    if (isset($setting['alias'])) {
                        // if alias is defined, calculate the other values
                        if (isset($setting['resourceId'])) {
                            $resourceId = $setting['resourceId'];
                        } elseif ($res = $this->modx->getObject('modResource', array('uri' => $setting['alias']))) {
                            $resourceId = $res->get('id');
                        } else {
                            // if resourceId could not be calculated, don't use that setting
                            if ($this->getOption('debug')) {
                                $this->modx->log(modX::LOG_LEVEL_INFO, 'CustomRequest Plugin: Could not calculate the resourceId for the given alias');
                            }
                            break;
                        }
                        $alias = $setting['alias'];
                    } elseif (isset($setting['resourceId'])) {
                        $resourceId = $setting['resourceId'];
                        if (isset($setting['alias'])) {
                            $alias = $setting['alias'];
                        } else {
                            $alias = '';
                        }
                    }
                    $config = $this->modx->newObject('CustomrequestConfigs');
                    $config->fromArray(array(
                        'name' => ucfirst($key),
                        'menuindex' => $i,
                        'alias' => $alias,
                        'resourceid' => $resourceId,
                        'urlparams' => json_encode($urlParams),
                        'regex' => $regEx
                    ));
                    $config->save();
                    $i++;
                }
            }
            return;
        }
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned, if the option is not found locally or as a namespaced system setting.
     * @param bool $skipEmpty If true: use default value if option value is empty.
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, $options = array(), $default = null, $skipEmpty = false)
    {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options !== null && array_key_exists($key, $options) && !($skipEmpty && empty($options[$key]))) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->options) && !($skipEmpty && empty($options[$key]))) {
                $option = $this->options[$key];
            } elseif (array_key_exists("{$this->namespace}.{$key}", $this->modx->config)) {
                $option = $this->modx->getOption("{$this->namespace}.{$key}", null, $default, $skipEmpty);
            }
        }
        return $option;
    }

    /**
     * Load all config files and prepare the values.
     *
     * @access public
     * @return void
     */
    public function initialize()
    {
        $configs = $this->modx->getCollection('CustomrequestConfigs');

        // TODO: Caching of the calculated values.
        // import config files
        foreach ($configs as $config) {
            // fill urlParams if defined
            $urlParams = ($tmp = json_decode($config->get('urlparams'))) ? $tmp : array();
            $regEx = $config->get('regex');
            if ($alias = $config->get('alias')) {
                // if alias is defined, calculate the other values
                if ($config->get('resourceid')) {
                    $resourceId = $config->get('resourceid');
                } elseif ($res = $this->modx->getObject('modResource', array('uri' => $config->get('alias')))) {
                    $resourceId = $res->get('id');
                } else {
                    // if resourceId could not be calculated or is not set, don't use that setting
                    if ($this->getOption('debug')) {
                        $this->modx->log(modX::LOG_LEVEL_INFO, 'CustomRequest Plugin: Could not calculate the resourceId for the given alias "' . $alias . '"');
                    }
                    break;
                }
            } elseif ($resourceId = $config->get('resourceid')) {
                // else if resourceId is defined, calculate the other values
                if ($config->get('alias')) {
                    $alias = $config->get('alias');
                } elseif ($resourceId && $alias = $this->modx->makeUrl($resourceId)) {
                    // cutoff trailing .html or /
                    $alias = trim(str_replace('.html', '', $alias), '/');
                } else {
                    // if alias could not be calculated, don't use that setting
                    if ($this->getOption('debug')) {
                        $this->modx->log(modX::LOG_LEVEL_INFO, 'CustomRequest Plugin: Could not calculate the alias for the given resourceId "' . $resourceId . '"');
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
        return;
    }

    /**
     * Check if the search string starts with one of the allowed aliases and
     * prepare the url param string if successful.
     *
     * @access public
     * @param string $search A string to search the allowed aliases in
     * @return boolean
     */
    public function searchAliases($search)
    {
        // strip cultureKey i.e. in Babel installations.
        $base = $this->modx->cultureKey.'/';

        if(substr($search, 0, strlen($base)) == $base)  {
            $search = substr($search, strlen($base));
        }

        $valid = false;
        // loop through the allowed aliases
        if (is_array($this->requests) && count($this->requests)) {
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
                    $valid = true;
                    break;
                }
            }
        } else {
            if ($this->getOption('debug')) {
                $this->modx->log(modX::LOG_LEVEL_INFO, 'CustomRequest Plugin: No valid configs found.');
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
    public function setRequest()
    {
        $params = str_replace('.html', '', $this->urlParams);
        if ($this->regEx) {
            $params = preg_match($this->regEx, $params);
        } else {
            $params = explode('/', trim($params, '/'));
        }
        if (count($params) >= 1) {
            $setting = $this->requests[$this->alias];
            // set the request parameters
            foreach ($params as $key => $value) {
                if (isset($setting['urlParams'][$key])) {
                    $_REQUEST[$setting['urlParams'][$key]] = $value;
                    $_GET[$setting['urlParams'][$key]] = $value;
                } else {
                    $_REQUEST['p' . ($key + 1)] = $value;
                    $_GET['p' . ($key + 1)] = $value;
                }
            }
        }
        $this->modx->sendForward($this->resourceId);
        return;
    }

}
