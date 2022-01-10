<?php
/**
 * Abstract processor
 *
 * @package customrequest
 * @subpackage processors
 */

namespace TreehillStudio\CustomRequest\Processors;

use modProcessor;
use modX;
use TreehillStudio\CustomRequest\CustomRequest;

/**
 * Class Processor
 */
abstract class Processor extends modProcessor
{
    public $languageTopics = ['customrequest:default'];

    /** @var CustomRequest */
    public $customrequest;

    /**
     * {@inheritDoc}
     * @param modX $modx A reference to the modX instance
     * @param array $properties An array of properties
     */
    function __construct(modX &$modx, array $properties = [])
    {
        parent::__construct($modx, $properties);

        $corePath = $this->modx->getOption('customrequest.core_path', null, $this->modx->getOption('core_path') . 'components/customrequest/');
        $this->customrequest = $this->modx->getService('customrequest', 'CustomRequest', $corePath . 'model/customrequest/');
    }

    abstract public function process();

    /**
     * Get a boolean property.
     * @param string $k
     * @param mixed $default
     * @return bool
     */
    public function getBooleanProperty($k, $default = null)
    {
        return ($this->getProperty($k, $default) === 'true' || $this->getProperty($k, $default) === true || $this->getProperty($k, $default) === '1' || $this->getProperty($k, $default) === 1);
    }
}
