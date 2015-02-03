<?php
/**
 * Resolve creating db tables
 *
 * THIS RESOLVER IS AUTOMATICALY GENERATED, NO CHANGES WILL APPLY
 *
 * @package customrequest
 * @subpackage build
 */

if ($object->xpdo) {
    $modx =& $object->xpdo;
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
            $modelPath = $modx->getOption('customrequest.core_path', null, $modx->getOption('core_path') . 'components/customrequest/') . 'model/';
            $modx->addPackage('customrequest', $modelPath);

            $manager = $modx->getManager();

            $manager->createObjectContainer('CustomrequestConfigs');

            break;
    }
}

return true;