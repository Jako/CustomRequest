<?php
/**
 * @package customrequest
 * @subpackage plugin
 */

namespace TreehillStudio\CustomRequest\Plugins\Events;

use TreehillStudio\CustomRequest\Plugins\Plugin;

class OnPageNotFound extends Plugin
{
    public function process()
    {
        $this->customrequest->initialize();
        if ($this->modx->context->get('key') !== 'mgr') {
            $requestParamAlias = $this->modx->getOption('request_param_alias', null, 'q');
            $requestUri = trim(strtok($_REQUEST[$requestParamAlias] ?? '', '?'), '/');
            if ($this->customrequest->searchAliases($requestUri)) {
                $this->customrequest->setRequest();
            }
        }
    }
}
