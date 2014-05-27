<?php
/**
 * CustomRequest
 *
 * Copyright 2013 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * CustomRequest is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * CustomRequest is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * CustomRequest; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package customrequest
 * @subpackage snippet
 *
 * @author      Thomas Jakobi (thomas.jakobi@partout.info)
 * @copyright   Copyright 2013, Thomas Jakobi
 * @version     1.0.2
 */
$customrequestCorePath = $modx->getOption('customrequest.core_path', null, $modx->getOption('core_path') . 'components/customrequest/');

$requestUri = trim($_SERVER['REQUEST_URI'], '/');

$customrequest = $modx->getService('customrequest', 'CustomRequest', $customrequestCorePath . 'model/customrequest/', $scriptProperties);
$customrequest->initialize();

$eventName = $modx->event->name;
switch ($eventName) {
    case 'OnPageNotFound':
        if ($customrequest->searchAliases($requestUri)) {
            $customrequest->setRequest();
        }
        break;
    case 'OnWebPagePrerender':
        /* TODO: replace not friendly URL parameter (for URLs with a valid CustomRequest config) with friendly ones */
        break;
}

return;
