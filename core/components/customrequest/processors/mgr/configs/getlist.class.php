<?php
/**
 * Get list processor for CustomRequest
 *
 * @package customrequest
 * @subpackage processor
 */

class CustomrequestConfigsGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'CustomrequestConfigs';
    public $languageTopics = array('customrequest:default');
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'customrequest.configs';

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
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
        /** @var modResource $resource */
        $resource = $this->modx->getObject('modResource', $ta['resourceid']);
        if ($resource) {
            // If the alias could be retrieved by a resource id or if the alias is a valid regular rexpression
            $ta['pagetitle'] = $resource->get('pagetitle') . ' (' . $ta['resourceid'] . ')';
            $ta['alias_gen'] = ($ta['alias']) ? $ta['alias'] : '<span class="green" title="' . $this->modx->lexicon('customrequest.configs_alias_generated') . '">' . $this->makeUrl($ta['resourceid'], $resource->get('context_key')) . '</span>';
        } else {
            $ta['resourceid'] = '';
            if (@preg_match($ta['alias'], 'dummy') !== false) {
                $ta['alias_gen'] = '<span class="blue" title="' . $this->modx->lexicon('customrequest.configs_alias_regex') . '">' . $ta['alias'] . '</span>';
            }
        }
        return $ta;
    }

    /**
     * @param integer $id
     * @param string $context
     * @return string
     */
    private function makeUrl($id, $context)
    {
        $url = $this->modx->makeUrl($id, $context);
        return str_replace($this->modx->getOption('site_url'), '', $url);
    }

}

return 'CustomrequestConfigsGetListProcessor';
