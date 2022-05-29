<?php
/**
 * Remove a Config
 *
 * @package customrequest
 * @subpackage processors
 */

use TreehillStudio\CustomRequest\Processors\ObjectRemoveProcessor;

class CustomRequestConfigsRemoveProcessor extends ObjectRemoveProcessor
{
    public $classKey = 'CustomrequestConfigs';
    public $objectType = 'customrequest.configs';

    /**
     * {@inheritDoc}
     * @return bool
     */
    public function afterRemove()
    {
        $this->customrequest->reset();

        return parent::afterRemove();
    }
}

return 'CustomRequestConfigsRemoveProcessor';
