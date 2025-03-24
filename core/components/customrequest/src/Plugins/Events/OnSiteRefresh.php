<?php
/**
 * @package customrequest
 * @subpackage plugin
 */

namespace TreehillStudio\CustomRequest\Plugins\Events;

use TreehillStudio\CustomRequest\Plugins\Plugin;
use xPDO;

class OnSiteRefresh extends Plugin
{
    public function process()
    {
        $this->customrequest->reset();
        $this->modx->log(xPDO::LOG_LEVEL_INFO, $this->modx->lexicon('customrequest.refresh_cache', [
            'packagename' => $this->customrequest->packageName
        ]));
   }
}
