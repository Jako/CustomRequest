<?php
/**
 * Sort Configs
 *
 * @package customrequest
 * @subpackage processors
 */

use TreehillStudio\CustomRequest\Processors\ObjectSortindexProcessor;

class CustomrequestConfigsSortindexProcessor extends ObjectSortindexProcessor
{
    public $classKey = 'CustomrequestConfigs';
    public $objectType = 'customrequest.configs';
    public $indexKey = 'menuindex';

    /**
     * {@inheritDoc}
     */
    protected function afterSorting()
    {
        $this->customrequest->reset();
    }
}

return 'CustomrequestConfigsSortindexProcessor';
