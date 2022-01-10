<?php
/**
 * Get list Configs
 *
 * @package customrequest
 * @subpackage processors
 */

use TreehillStudio\CustomRequest\Processors\ObjectGetListProcessor;

class CustomrequestConfigsGetListProcessor extends ObjectGetListProcessor
{
    public $classKey = 'CustomrequestConfigs';
    public $defaultSortField = 'menuindex';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'customrequest.configs';

    protected $search = ['name', 'alias'];

    /**
     * {@inheritDoc}
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $ta = $object->toArray('', false, true);
        /** @var modResource $resource */
        $resource = $this->modx->getObject('modResource', $ta['resourceid']);
        if ($resource) {
            $context = $this->modx->getContext($resource->get('context_key'));
            // If the alias could be retrieved by a resource id or if the alias is a valid regular rexpression
            $ta['pagetitle'] = $resource->get('pagetitle') . ' (' . $ta['resourceid'] . ')';
            $ta['alias_gen'] = ($ta['alias']) ?: '<span class="green" title="' . $this->modx->lexicon('customrequest.configs_alias_generated') . '">' . $this->makeUrl($ta['resourceid'], $resource->get('context_key')) . '</span>';
            $ta['context'] = ($context->get('name')) ? $context->get('name') . ' (' . $resource->get('context_key') . ')' : $resource->get('context_key');
        } else {
            $ta['resourceid'] = '';
            if (!$this->customrequest->isRegularExpression($ta['alias'])) {
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
        $resource = $this->modx->getObject('modResource', $id);
        $tmpKey = $this->modx->context->key;
        $contextKey = $resource->get('context_key');
        $this->modx->switchContext($contextKey);
        $url = $this->modx->makeUrl($id, $context);
        $this->modx->switchContext($tmpKey);
        return str_replace($this->modx->getOption('site_url'), '', $url);
    }
}

return 'CustomrequestConfigsGetListProcessor';
