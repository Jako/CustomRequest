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
}

return 'CustomRequestConfigsRemoveProcessor';
