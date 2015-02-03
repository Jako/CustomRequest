<?php
/**
 * Update From Grid
 *
 * @package customrequest
 * @subpackage processor
 */
require_once(dirname(__FILE__) . '/update.class.php');

class CustomRequestConfigsUpdateFromGridProcessor extends CustomRequestConfigsUpdateProcessor
{
    public function initialize()
    {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $data = $this->modx->fromJSON($data);
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $this->setProperties($data);
        $this->unsetProperty('data');

        return parent::initialize();
    }
}

return 'CustomRequestConfigsUpdateFromGridProcessor';