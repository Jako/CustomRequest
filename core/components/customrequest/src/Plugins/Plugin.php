<?php
/**
 * Abstract plugin
 *
 * @package customrequest
 * @subpackage plugin
 */

namespace TreehillStudio\CustomRequest\Plugins;

use modX;
use CustomRequest;

/**
 * Class Plugin
 */
abstract class Plugin
{
    /** @var modX $modx */
    protected $modx;
    /** @var CustomRequest $customrequest */
    protected $customrequest;
    /** @var array $scriptProperties */
    protected $scriptProperties;

    /**
     * Plugin constructor.
     *
     * @param $modx
     * @param $scriptProperties
     */
    public function __construct($modx, &$scriptProperties)
    {
        $this->scriptProperties = &$scriptProperties;
        $this->modx =& $modx;
        $corePath = $this->modx->getOption('customrequest.core_path', null, $this->modx->getOption('core_path') . 'components/customrequest/');
        $this->customrequest = $this->modx->getService('customrequest', 'CustomRequest', $corePath . 'model/customrequest/', [
            'core_path' => $corePath
        ]);
    }

    /**
     * Run the plugin event.
     */
    public function run()
    {
        $init = $this->init();
        if ($init !== true) {
            return;
        }

        $this->process();
    }

    /**
     * Initialize the plugin event.
     *
     * @return bool
     */
    public function init()
    {
        return true;
    }

    /**
     * Process the plugin event code.
     *
     * @return mixed
     */
    abstract public function process();
}