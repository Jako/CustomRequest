<?php
/**
 * Sortmenuindex processor for CustomRequest CMP
 *
 * @package customrequest
 * @subpackage processor
 */

// Clean up the sorting first
$c = $modx->newQuery('CustomrequestConfigs');
$c->sortby('menuindex', 'ASC');
$c->sortby('id', 'ASC');
$configs = $modx->getCollection('CustomrequestConfigs', $c);
if (!$configs) {
    return $this->failure();
}
$i = 0;
foreach ($configs as $config) {
    $config->set('menuindex', $i);
    $config->save();
    $i++;
}

$targetMenuindex = $modx->getObject('CustomrequestConfigs', $scriptProperties['targetId'])->get('menuindex');

// Prepare the moving ids
$movingIds = @explode(',', $scriptProperties['movingIds']);
$c = $modx->newQuery('CustomrequestConfigs');
$c->where(array(
    'id:IN' => $movingIds
));
$c->sortby('menuindex', 'ASC');
$c->sortby('id', 'ASC');
$countMovingRes = $modx->getCount('CustomrequestConfigs', $c);
$movingRes = $modx->getCollection('CustomrequestConfigs', $c);
foreach ($movingRes as $res) {
    $c = $modx->newQuery('CustomrequestConfigs');
    $movingMenuindex = $res->get('menuindex');
    if ($movingMenuindex < $targetMenuindex) {
        $c->where(array(
            'menuindex:>' => $movingMenuindex,
            'menuindex:<=' => $targetMenuindex,
        ));
    } else {
        $c->where(array(
            'menuindex:<' => $movingMenuindex,
            'menuindex:>=' => $targetMenuindex,
        ));
    }
    $c->sortby('menuindex', 'ASC');
    $c->sortby('id', 'ASC');
    $affectedRes = $modx->getCollection('CustomrequestConfigs', $c);
    foreach ($affectedRes as $affected) {
        $affectedMenuindex = $affected->get('menuindex');
        if ($movingMenuindex < $targetMenuindex) {
            $newIndex = $affectedMenuindex - 1;
        } else {
            $newIndex = $affectedMenuindex + 1;
        }
        $affected->set('menuindex', $newIndex);
        $affected->save();
    }
    $res->set('menuindex', $targetMenuindex);
    $res->save();
}

return $this->success();