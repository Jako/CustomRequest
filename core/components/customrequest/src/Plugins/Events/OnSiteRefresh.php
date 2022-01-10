<?php
/**
 * @package customrequest
 * @subpackage plugin
 */

namespace TreehillStudio\CustomRequest\Plugins\Events;

use TreehillStudio\CustomRequest\Plugins\Plugin;

class OnSiteRefresh extends Plugin
{
    public function process()
    {
        $this->customrequest->reset();
    }
}
