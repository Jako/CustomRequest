<?php
/**
 * Remove processor for CustomRequest
 *
 * @package customrequest
 * @subpackage processor
 */

class CustomRequestConfigsRemoveProcessor extends modObjectRemoveProcessor
{
    public $classKey = 'CustomrequestConfigs';
    public $languageTopics = array('customrequest:default');
    public $objectType = 'customrequest.configs';

    /**
     * @return bool
     */
    public function afterRemove()
    {
        $path = $this->modx->getOption('customrequest.core_path', null, $this->modx->getOption('core_path') . 'components/customrequest/');
        $customrequest = $this->modx->getService('customrequest', 'CustomRequest', $path . 'model/customrequest/', array(
            'core_path' => $path
        ));
        $customrequest->reset();

        return parent::afterRemove();
    }
}

return 'CustomRequestConfigsRemoveProcessor';
