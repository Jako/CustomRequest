<?php
/**
 * Update a Config
 *
 * @package customrequest
 * @subpackage processors
 */

use TreehillStudio\CustomRequest\Processors\ObjectUpdateProcessor;

class CustomrequestConfigsUpdateProcessor extends ObjectUpdateProcessor
{
    public $classKey = 'CustomrequestConfigs';
    public $objectType = 'customrequest.configs';

    protected $required = ['name'];

    /**
     * {@inheritDoc}
     * @return bool
     */
    public function beforeSave()
    {
        $alias = $this->getProperty('alias');
        $regex = $this->getProperty('regex');
        $resourceid = $this->getProperty('resourceid');

        if (empty($alias) && empty($resourceid)) {
            $this->addFieldError('alias', $this->modx->lexicon('customrequest.configs_err_ns_alias_resourceid'));
            $this->addFieldError('resourceid', $this->modx->lexicon('customrequest.configs_err_ns_alias_resourceid'));
        }

        if (empty($resourceid) && !$this->customrequest->isRegularExpression($alias)) {
            $this->addFieldError('alias', $this->modx->lexicon('customrequest.configs_err_nv_alias_regex'));
        }

        if (!empty($regex) && !$this->customrequest->isRegularExpression($regex)) {
            $this->addFieldError('regex', $this->modx->lexicon('customrequest.configs_err_nv_regex'));
        }

        return parent::beforeSave();
    }

    /**
     * {@inheritDoc}
     * @return bool
     */
    public function afterSave()
    {
        $this->customrequest->reset();

        return parent::afterSave();
    }
}

return 'CustomrequestConfigsUpdateProcessor';
