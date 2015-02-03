<?php
/**
 * Get list processor for CustomRequest CMP
 *
 * @package customrequest
 * @subpackage processor
 */
class CustomrequestConfigsGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'CustomrequestConfigs';
    public $languageTopics = array('customrequest:default');
    public $objectType = 'customrequest.configs';

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                'name:LIKE' => '%' . $query . '%',
                'OR:alias:LIKE' => '%' . $query . '%'
            ));
        }
        $c->sortby('menuindex', 'ASC');
        return $c;
    }

    public function prepareRow(xPDOObject $object)
    {
        $ta = $object->toArray('', false, true);
        $resource = $this->modx->getObject('modResource', $ta['resourceid']);
        $ta['pagetitle'] = $resource->get('pagetitle');
        return $ta;
    }

}

return 'CustomrequestConfigsGetListProcessor';
