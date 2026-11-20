<?php
/**
 * CustomRequest
 *
 * Copyright 2013-2026 by Thomas Jakobi <office@treehillstudio.com>
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
    public $version = '1.3.14';

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
     * The request is forwarded by CustomRequest
     * @var bool $forwarding
     */
    public $forwarding = false;

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
            'matchPathBoundary' => (bool)$this->getOption('match_path_boundary', $options, true),
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

        // An empty array is a valid cache value, if there are no configs
        if (!is_array($this->requests) || $this->getOption('debug')) {
            // Clear the requests in debug mode
            $this->requests = [];

            // Import config records
            $c = $this->modx->newQuery('CustomrequestConfigs');
            $c->sortby('menuindex');
            $c->sortby('id');
            /** @var CustomrequestConfigs[] $configs */
            $configs = $this->modx->getCollection('CustomrequestConfigs', $c);
            $htmlExtension = $this->getHtmlExtension();
            foreach ($configs as $config) {
                // Fill additional urlParams if defined
                $urlParams = $this->getUrlParams($config->get('urlparams'));
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
                                continue;
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
                                    // Cutoff a trailing / or HTML extension
                                    $alias = $this->stripExtension(rtrim($alias, '/'), $htmlExtension);
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
                if ($resourceId && !$this->isAvailableResource($resourceId)) {
                    // A not found URI can't be forwarded to a deleted or unpublished resource, don't use that setting
                    if ($this->getOption('debug')) {
                        $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'The resource with ID "' . $resourceId . '" is deleted or unpublished.', '', 'CustomRequest Plugin');
                    }
                    continue;
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
            $htmlExtension = $this->getHtmlExtension();
            foreach ($this->requests as $request) {
                if (!$request['aliasRegEx']) {
                    // Check if searched string starts with the alias at a path boundary and for the contextKey
                    if ($request['alias'] && $this->startsWithAlias($search, $request['alias'], $htmlExtension) && $request['contextKey'] == $this->modx->context->key) {
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
                        if ($resourceId && $this->isAvailableResource($resourceId)) {
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
        $params = $this->stripExtension($this->found['urlParams'], $this->getHtmlExtension());
        if ($this->found['regEx']) {
            // The remaining URI part starts with a slash, if the alias path is set without a trailing slash
            $params = ltrim($params, '/');
            if (!preg_match($this->found['regEx'], $params, $matches)) {
                // Return without redirecting
                return;
            }
            // $matches[0] contains the full match, we don't want that, and named subpatterns are contained twice
            array_shift($matches);
            $params = array_values(array_filter($matches, 'is_int', ARRAY_FILTER_USE_KEY));
        } else {
            $params = trim($params, '/');
            $params = ($params !== '') ? explode('/', $params) : [];
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
        // If MODX can't forward to the resource, it sends the error page and invokes OnPageNotFound again
        $this->forwarding = true;
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
        if (!is_string($string) || $string === '') {
            return false;
        }
        set_error_handler(function () {
        }, E_WARNING);
        $isRegularExpression = preg_match($string, '') !== FALSE;
        restore_error_handler();
        return $isRegularExpression;
    }

    /**
     * Check if the string is empty or a JSON array of parameter names
     *
     * @param string|null $string
     * @return bool
     */
    public function isUrlParams($string)
    {
        if (trim((string)$string) === '') {
            return true;
        }
        $urlParams = json_decode($string, true);
        if (!is_array($urlParams) || $urlParams !== array_values($urlParams)) {
            return false;
        }
        foreach ($urlParams as $urlParam) {
            if (!is_string($urlParam) || $urlParam === '') {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the parameter names of a JSON array of parameter names
     *
     * @param string|null $string
     * @return array
     */
    public function getUrlParams($string)
    {
        return $this->isUrlParams($string) ? (json_decode((string)$string, true) ?: []) : [];
    }

    /**
     * Check if a resource is not deleted and published
     *
     * @param int $resourceId
     * @return bool
     */
    public function isAvailableResource($resourceId)
    {
        return (bool)$this->modx->getCount('modResource', [
            'id' => (int)$resourceId,
            'deleted' => false,
            'published' => true
        ]);
    }

    /**
     * Check if the search string starts with the alias (at a path boundary, if
     * the match_path_boundary system setting is enabled)
     *
     * @param string $search
     * @param string $alias
     * @param string $extension
     * @return bool
     */
    public function startsWithAlias($search, $alias, $extension = '')
    {
        if (0 !== strpos($search, $alias)) {
            return false;
        }
        if (!$this->getOption('matchPathBoundary', null, true)) {
            return true;
        }
        $remaining = (string)substr($search, strlen($alias));
        return $remaining === '' ||
            substr($alias, -1) === '/' ||
            $remaining[0] === '/' ||
            ($extension !== '' && $remaining === $extension);
    }

    /**
     * Strip a trailing extension from a string
     *
     * @param string $string
     * @param string $extension
     * @return string
     */
    public function stripExtension($string, $extension)
    {
        $string = (string)$string;
        if ($extension !== '' && substr($string, -strlen($extension)) === $extension) {
            $string = substr($string, 0, -strlen($extension));
        }
        return $string;
    }

    /**
     * Get the extension for the MODX HTML content type
     *
     * @return string
     */
    public function getHtmlExtension()
    {
        $htmlContentType = $this->modx->getObject('modContentType', ['name' => 'HTML']);
        return ($htmlContentType) ? (string)$htmlContentType->get('file_extensions') : '.html';
    }

    /**
     * Get the options used by the manager scripts, so that no server paths are output in the manager
     *
     * @param array|null $options Options to filter instead of the class options
     * @return array
     */
    public function getManagerOptions(?array $options = null): array
    {
        return array_intersect_key($options ?? $this->options, array_flip([
            'assetsUrl',
            'connectorUrl',
            'debug',
            'is_admin',
            'modxversion',
            'version',
        ]));
    }
}
