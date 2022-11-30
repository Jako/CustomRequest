<?php
/**
 * Get list Resources
 *
 * @package customreqest
 * @subpackage processors
 */

use TreehillStudio\CustomRequest\Processors\ObjectGetListProcessor;

class CustomrequestResourcesGetListProcessor extends ObjectGetListProcessor
{
    public $classKey = 'modResource';
    public $defaultSortField = 'pagetitle';
    public $defaultSortDirection = 'DESC';
    public $objectType = 'customreqest.resources';

    protected $search = ['pagetitle', 'alias'];

    /**
     * (@inheritDoc}
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c = parent::prepareQueryBeforeCount($c);
        $c->where([
            'deleted' => false,
            'published' => true
        ]);
        return $c;
    }

    /**
     * (@inheritDoc}
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
