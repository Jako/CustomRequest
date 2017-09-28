<?php
/**
 * Get list processor for CustomRequest
 *
 * @package customreqest
 * @subpackage processor
 */

class CustomrequestResourcesGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'modResource';
    public $languageTopics = array('customreqest:default');
    public $defaultSortField = 'pagetitle';
    public $defaultSortDirection = 'DESC';
    public $objectType = 'customreqest.resources';

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                'pagetitle:LIKE' => '%' . $query . '%'
            ));
        }
        $c->where(array(
            'deleted' => false,
            'published' => true
        ));
        $c->sortby('pagetitle', 'ASC');
        return $c;
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $id = $this->getProperty('id');
        if (!empty($id)) {
            $c->where(array(
                'id' => $id
            ));
        }
        return $c;
    }

    /**
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $ta = $object->toArray('', false, true);
        $ta['pagetitle'] = $ta['pagetitle'] . ' (' . $ta['id'] . ')';
        return $ta;
    }

}

return 'CustomrequestResourcesGetListProcessor';
