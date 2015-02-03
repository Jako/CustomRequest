<?php
/**
 * Get list processor for CustomRequest CMP.
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

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                'pagetitle:LIKE' => '%'.$query.'%'
            ));
        }
        $c->where(array(
            'deleted' => false,
            'published' => true
        ));
        $c->sortby('pagetitle', 'ASC');
        return $c;
    }

}

return 'CustomrequestResourcesGetListProcessor';
