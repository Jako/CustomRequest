<?php
/**
 * CustomRequest
 *
 * Copyright 2013-2025 by Thomas Jakobi <office@treehillstudio.com>
 *
 * @package customrequest
 * @subpackage classfile
 */

namespace TreehillStudio\CustomRequest;

use CustomrequestConfigs;
use modResource;
use modX;
use xPDO;

/**
 * class CustomRequest
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
     * The package name
     * @var string $packageName
     */
    public $packageName = 'CustomRequest';

    /**
     * The version
     * @var string $version
     */
    public $version = '1.3.12';

    /**
     * The class options
     * @var array $options
     */
    public $options = [];

    /**
     * The requests array
     * @var array $requests
     */
    public $requests = [];

    /**
     * The found request
     * @var array $found
     */
    public $found = [];

    /**
     * CustomRequest constructor
     *
     * @param modX $modx A reference to the modX instance.
     * @param array $options An array of options. Optional.
     */
    public function __construct(modX &$modx, $options = [])
    {
        $this->modx =& $modx;

        $corePath = $this->getOption('core_path', $options, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/' . $this->namespace . '/');
        $assetsPath = $this->getOption('assets_path', $options, $this->modx->getOption('assets_path', null, MODX_ASSETS_PATH) . 'components/' . $this->namespace . '/');
        $assetsUrl = $this->getOption('assets_url', $options, $this->modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/' . $this->namespace . '/');
        $modxversion = $this->modx->getVersionData();

        // Load some default paths for easier management
        $this->options = array_merge([
            'namespace' => $this->namespace,
            'version' => $this->version,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'vendorPath' => $corePath . 'vendor/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'pagesPath' => $corePath . 'elements/pages/',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'pluginsPath' => $corePath . 'elements/plugins/',
            'controllersPath' => $corePath . 'controllers/',
            'processorsPath' => $corePath . 'processors/',
            'templatesPath' => $corePath . 'templates/',
            'assetsPath' => $assetsPath,
            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $assetsUrl . 'connector.php',
        ], $options);

        $lexicon = $this->modx->getService('lexicon', 'modLexicon');
        $lexicon->load($this->namespace . ':default');

        $this->packageName = $this->modx->lexicon('customrequest');

        $this->modx->addPackage($this->namespace, $this->getOption('modelPath'));

        // Add default options
        $this->options = array_merge($this->options, [
            'debug' => (bool)$this->getOption('debug', $options, false),
            'modxversion' => $modxversion['version'],
            'is_admin' => $this->modx->user && $this->modx->context && ($modx->hasPermission('settings') || $modx->hasPermission($this->namespace . '_settings')),
            'configsPath' => $this->getOption('configsPath', null, $corePath . 'configs/'),
            'cachePath' => $this->modx->getOption('core_path') . 'cache/',
            'cacheKey' => 'requests',
            'cacheOptions' => [
                xPDO::OPT_CACHE_KEY => 'customrequest',
                xPDO::OPT_CACHE_HANDLER => $modx->getOption('cache_resource_handler', null, $this->modx->getOption(xPDO::OPT_CACHE_HANDLER, null, 'xPDOFileCache')),
            ],
        ]);

        if ($this->getOption('aliases')) {
            $this->requests = json_decode($this->getOption('aliases'), true);
        }
        if (!$this->requests) {
            $this->requests = [];
        }
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned if the option is not found locally or as a
     * namespaced system setting; by default this value is null.
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, $options = [], $default = null)
    {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options != null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            } elseif (array_key_exists("$this->namespace.$key", $this->modx->config)) {
                $option = $this->modx->getOption("$this->namespace.$key");
            }
        }
        return $option;
    }

    /**
     * Load all config files and prepare the values.
     */
    public function initialize()
    {
        $this->requests = $this->modx->cacheManager->get($this->getOption('cacheKey'), $this->getOption('cacheOptions'));

        if (empty($this->requests) || $this->getOption('debug')) {
            // Clear the requests in debug mode
            $this->requests = [];

            // Import config records
            $c = $this->modx->newQuery('CustomrequestConfigs');
            $c->sortby('menuindex');
            /** @var CustomrequestConfigs[] $configs */
            $configs = $this->modx->getCollection('CustomrequestConfigs', $c);
            foreach ($configs as $config) {
                // Fill additional urlParams if defined
                $urlParams = json_decode($config->get('urlparams')) ? json_decode($config->get('urlparams')) : [];
                $resourceId = $config->get('resourceid');
                $alias = $config->get('alias');
                $aliasRegEx = false;
                $regEx = $config->get('regex');
                $contextKey = '';
                if ($alias) {
                    // If alias is defined, calculate the other values
                    if (!$resourceId) {
                        $resourceId = 0;
                        if (!$this->isRegularExpression($alias)) {
                            $resourceId = $this->modx->findResource($alias);
                            if (!$resourceId) {
                                // If resourceId could not be calculated and alias is not a valid regular expression, don't use that setting
                                if ($this->getOption('debug')) {
                                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not calculate the resourceId for the given alias "' . $alias . '".', '', 'CustomRequest Plugin');
                                }
                                break;
                            }
                        } else {
                            $aliasRegEx = true;
                        }
                    }
                    if ($resourceId) {
                        $resource = $this->modx->getObject('modResource', $resourceId);
                        if ($resource) {
                            $contextKey = $resource->get('context_key');
                        }
                    }
                } else {
                    $resourceId = $config->get('resourceid');
                    if ($resourceId) {
                        // If alias is not defined and resourceId is defined, calculate the other values
                        if ($config->get('alias')) {
                            $alias = $config->get('alias');
                        } else {
                            /** @var modResource $resource */
                            $resource = $this->modx->getObject('modResource', $resourceId);
                            if ($resource) {
                                $contextKey = $resource->get('context_key');
                                $alias = $resource->get('uri');
                                // If the resource uri field is empty try to get it with make url
                                if (!$alias) {
                                    $tmpKey = $this->modx->context->key;
                                    $this->modx->switchContext($contextKey);
                                    $alias = $this->modx->makeUrl($resourceId);
                                    $this->modx->switchContext($tmpKey);
                                }
                                if ($alias) {
                                    // Cutoff trailing .html or /
                                    $alias = rtrim(str_replace('.html', '', $alias), '/');
                                } else {
                                    // If alias could not be calculated, don't use that setting
                                    if ($this->getOption('debug')) {
                                        $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not calculate the alias for the given resourceId "' . $resourceId . '".', '', 'CustomRequest Plugin');
                                    }
                                    continue;
                                }
                            } else {
                                // If alias could not be calculated, don't use that setting
                                if ($this->getOption('debug')) {
                                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'No resource with ID "' . $resourceId . '" found.', '', 'CustomRequest Plugin');
                                }
                                continue;
                            }
                        }
                    }
                }
                $this->requests[$contextKey . ':' . $alias] = [
                    'resourceId' => $resourceId,
                    'alias' => $alias,
                    'aliasRegEx' => $aliasRegEx,
                    'urlParams' => $urlParams,
                    'regEx' => $regEx,
                    'contextKey' => $contextKey
                ];
            }
            $this->modx->cacheManager->set($this->getOption('cacheKey'), $this->requests, 0, $this->getOption('cacheOptions'));
        }
    }

    /**
     * Reset the customrequest cache partition
     */
    public function reset()
    {
        $this->modx->cacheManager->delete($this->getOption('cacheKey'), $this->getOption('cacheOptions'));
    }

    /**
     * Check if the search string starts with one of the allowed aliases and
     * prepare the url param string if successful.
     *
     * @param string $search A string to search the allowed aliases in
     * @return boolean
     */
    public function searchAliases($search)
    {
        // Strip cultureKey i.e. in Babel installations.
        if (0 === strpos($search, $this->modx->cultureKey . '/')) {
            $search = substr($search, strlen($this->modx->cultureKey) + 1);
        }

        $valid = false;
        // Loop through the allowed aliases
        if (is_array($this->requests) && count($this->requests)) {
            foreach ($this->requests as $request) {
                if (!$request['aliasRegEx']) {
                    // Check if searched string starts with the alias and for the contextKey
                    if ($request['alias'] && 0 === strpos($search, $request['alias']) && $request['contextKey'] == $this->modx->context->key) {
                        $this->found = [
                            // Strip alias from seached string and urldecode it
                            'urlParams' => urldecode(substr($search, strlen($request['alias']))),
                            // Set the found resource id
                            'resourceId' => $request['resourceId'],
                            // Set the found alias
                            'alias' => $request['alias'],
                            // Set the found regEx
                            'regEx' => $request['regEx'],
                            // Set the found regEx
                            'contextKey' => $request['contextKey']
                        ];
                        $valid = true;
                        break;
                    }
                } else {
                    if (preg_match($request['alias'], $search, $matches)) {
                        if (isset($matches[1])) {
                            $alias = rtrim(str_replace($matches[1], '', $matches[0]), '/');
                        } else {
                            $alias = rtrim($matches[0], '/');
                        }
                        $resourceId = $this->modx->findResource($alias . $this->modx->getOption('container_suffix'), $request['contextKey']);
                        $resourceId = ($resourceId) ?: $this->modx->findResource($alias . $this->getHtmlExtension(), $request['contextKey']);
                        if ($resourceId) {
                            $this->found = [
                                // Strip alias from seached string and urldecode it
                                'urlParams' => urldecode(substr($search, strlen($matches[0]))),
                                // Set the found resource id
                                'resourceId' => $resourceId,
                                // Set the found alias
                                'alias' => $request['alias'],
                                // Set the found regEx
                                'regEx' => $request['regEx'],
                                // Set the found regEx
                                'contextKey' => $request['contextKey']
                            ];
                            $valid = true;
                            break;
                        }
                    }
                }
            }
        } else {
            if ($this->getOption('debug')) {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'No valid configs found.', '', 'CustomRequest Plugin');
            }
        }

        return $valid;
    }

    /**
     * Prepare the request parameters.
     */
    public function setRequest()
    {
        $params = str_replace('.html', '', $this->found['urlParams']);
        if ($this->found['regEx']) {
            if (!preg_match($this->found['regEx'], $params, $matches)) {
                // Return without redirecting
                return;
            }
            // $matches[0] contains the full match, we don't want that
            array_shift($matches);
            $params = $matches;
        } else {
            $params = explode('/', rtrim($params, '/'));
        }
        if (count($params) >= 1) {
            $setting = $this->requests[$this->found['contextKey'] . ':' . $this->found['alias']];

            $foundParams = [];
            // Set the request parameters
            foreach ($params as $key => $value) {
                if (isset($setting['urlParams'][$key])) {
                    $_REQUEST[$setting['urlParams'][$key]] = $value;
                    $_GET[$setting['urlParams'][$key]] = $value;
                    $foundParams[$setting['urlParams'][$key]] = $value;
                } else {
                    $_REQUEST['p' . ($key + 1)] = $value;
                    $_GET['p' . ($key + 1)] = $value;
                    $foundParams['p' . ($key + 1)] = $value;
                }
            }
            if ($this->getOption('debug')) {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Used configuration:' . "\n" . print_r($setting, true) . "\n" . 'Set params:' . "\n" . print_r($foundParams, true), '', 'CustomRequest Plugin');
            }
        }
        if ($resource = $this->modx->getObject('modResource', $this->found['resourceId'])) {
            if ($this->modx->context->key != $resource->get('context_key')) {
                $this->modx->switchContext($resource->get('context_key'));

                // Set locale after context switch since $this->modx->_initCulture is called before OnPageNotFound
                if ($this->modx->context && $this->modx->getOption('setlocale', null, true)) {
                    $locale = setlocale(LC_ALL, '0');
                    setlocale(LC_ALL, $this->modx->context->getOption('locale', null, $locale));
                }
            }
        }
        $this->modx->sendForward($this->found['resourceId']);
    }

    /**
     * Check for a valid regular expression
     *
     * @param $string
     * @return bool
     */
    public function isRegularExpression($string)
    {
        set_error_handler(function () {
        }, E_WARNING);
        $isRegularExpression = preg_match($string, '') !== FALSE;
        restore_error_handler();
        return $isRegularExpression;
    }

    /**
     * Get the extension for the MODX HTML content type
     *
     * @return string
     */
    public function getHtmlExtension()
    {
        $htmlContentType = $this->modx->getObject('modContentType', ['name' => 'HTML']);
        return ($htmlContentType) ? $htmlContentType->get('file_extensions') : '.html';
    }
}
