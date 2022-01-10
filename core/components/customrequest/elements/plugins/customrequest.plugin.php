<?php
/**
 * CustomRequest plugin
 *
 * @package customrequest
 * @subpackage plugin
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$className = 'TreehillStudio\CustomRequest\Plugins\Events\\' . $modx->event->name;

$corePath = $modx->getOption('customrequest.core_path', null, $modx->getOption('core_path') . 'components/customrequest/');
/** @var CustomRequest $customrequest */
$customrequest = $modx->getService('customrequest', 'CustomRequest', $corePath . 'model/customrequest/', [
    'core_path' => $corePath
]);

if ($customrequest) {
    if (class_exists($className)) {
        $handler = new $className($modx, $scriptProperties);
        if (get_class($handler) == $className) {
            $handler->run();
        } else {
            $modx->log(xPDO::LOG_LEVEL_ERROR, $className. ' could not be initialized!', '', 'CustomRequest Plugin');
        }
    } else {
        $modx->log(xPDO::LOG_LEVEL_ERROR, $className. ' was not found!', '', 'CustomRequest Plugin');
    }
}

return;