<?php
/**
 * Remove processor for CustomRequest CMP
 *
 * @package customrequest
 * @subpackage processor
 */
class CustomRequestConfigsRemoveProcessor extends modObjectRemoveProcessor
{
    public $classKey = 'CustomrequestConfigs';
    public $languageTopics = array('customrequest:default');
    public $objectType = 'customrequest.configs';

    public function afterRemove()
    {
        $customrequestCorePath = $this->modx->getOption('customrequest.core_path', null, $this->modx->getOption('core_path') . 'components/customrequest/');
        $customrequest = $this->modx->getService('customrequest', 'CustomRequest', $customrequestCorePath . 'model/customrequest/', array());
        $customrequest->reset();

        return parent::afterRemove();
    }
}

return 'CustomRequestConfigsRemoveProcessor';
