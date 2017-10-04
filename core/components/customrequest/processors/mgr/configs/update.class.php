<?php
/**
 * Update processor for CustomRequest
 *
 * @package customrequest
 * @subpackage processor
 */

class CustomrequestConfigsUpdateProcessor extends modObjectUpdateProcessor
{
    public $classKey = 'CustomrequestConfigs';
    public $languageTopics = array('customrequest:default');
    public $objectType = 'customrequest.configs';

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $name = $this->getProperty('name');
        $alias = $this->getProperty('alias');
        $regex = $this->getProperty('regex');
        $resourceid = $this->getProperty('resourceid');

        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('field_required'));
        }

        if (empty($alias) && empty($resourceid)) {
            $this->addFieldError('alias', $this->modx->lexicon('customrequest.configs_err_ns_alias_resourceid'));
            $this->addFieldError('resourceid', $this->modx->lexicon('customrequest.configs_err_ns_alias_resourceid'));
        }

        if (empty($resourceid) && (@preg_match($alias, 'dummy') === false)) {
            $this->addFieldError('alias',$this->modx->lexicon('customrequest.configs_err_nv_alias_regex'));
        }

        if (!empty($regex) && (@preg_match($regex, 'dummy') === false)) {
            $this->addFieldError('regex',$this->modx->lexicon('customrequest.configs_err_nv_regex'));
        }

        if (!$this->hasErrors()) {
            $path = $this->modx->getOption('customrequest.core_path', null, $this->modx->getOption('core_path') . 'components/customrequest/');
            $customrequest = $this->modx->getService('customrequest', 'CustomRequest', $path . 'model/customrequest/', array(
                'core_path' => $path
            ));
            $customrequest->reset();
        }

        return parent::beforeSave();
    }
}

return 'CustomrequestConfigsUpdateProcessor';
