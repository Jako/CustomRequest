<?php
/**
 * Update processor for CustomRequest CMP
 *
 * @package customrequest
 * @subpackage processor
 */
class CustomrequestConfigsUpdateProcessor extends modObjectUpdateProcessor
{
    public $classKey = 'CustomrequestConfigs';
    public $languageTopics = array('customrequest:default');
    public $objectType = 'customrequest.configs';

    public function beforeSave()
    {
        $name = $this->getProperty('name');
        $alias = $this->getProperty('alias');
        $resourceid = $this->getProperty('resourceid');

        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('field_required'));
        }

        if (empty($alias) && empty($resourceid)) {
            $this->addFieldError('alias',$this->modx->lexicon('customrequest.configs_err_ns_alias_resourceid'));
            $this->addFieldError('resourceid',$this->modx->lexicon('customrequest.configs_err_ns_alias_resourceid'));
        }

        return parent::beforeSave();
    }
}

return 'CustomrequestConfigsUpdateProcessor';