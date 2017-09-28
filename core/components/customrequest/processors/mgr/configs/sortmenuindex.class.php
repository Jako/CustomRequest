<?php
/**
 * Sortmenuindex processor for CustomRequest
 *
 * @package customrequest
 * @subpackage processor
 */

class CustomrequestConfigsSortmenuindexProcessor extends modObjectProcessor
{
    public $classKey = 'CustomrequestConfigs';
    public $languageTopics = array('customrequest:default');
    public $objectType = 'customrequest.repositories';
    public $indexKey = 'menuindex';

    public function process()
    {
        // Clean up the sorting first
        $c = $this->modx->newQuery($this->classKey);
        $c->sortby($this->indexKey, 'ASC');
        $c->sortby('id', 'ASC');
        /** @var CustomrequestConfigs[] $configs */
        $configs = $this->modx->getIterator($this->classKey, $c);
        if (!$configs) {
            return $this->failure();
        }
        $i = 0;
        foreach ($configs as $config) {
            $config->set($this->indexKey, $i);
            $config->save();
            $i++;
        }

        $targetId = $this->getProperty('targetId');
        $targetIndex = $this->modx->getObject($this->classKey, $targetId)->get($this->indexKey);

        // Prepare the moving ids
        $movingIds = @explode(',', $this->getProperty('movingIds'));
        $c = $this->modx->newQuery($this->classKey);
        $c->where(array(
            'id:IN' => $movingIds
        ));
        $c->sortby($this->indexKey, 'ASC');
        $c->sortby('id', 'ASC');
        /** @var CustomrequestConfigs[] $movingRes */
        $movingRes = $this->modx->getIterator($this->classKey, $c);
        foreach ($movingRes as $res) {
            $c = $this->modx->newQuery($this->classKey);
            $movingIndex = $res->get($this->indexKey);
            if ($movingIndex < $targetIndex) {
                $c->where(array(
                    $this->indexKey . ':>' => $movingIndex,
                    $this->indexKey . ':<=' => $targetIndex,
                ));
            } else {
                $c->where(array(
                    $this->indexKey . ':<' => $movingIndex,
                    $this->indexKey . ':>=' => $targetIndex,
                ));
            }
            $c->sortby($this->indexKey, 'ASC');
            $c->sortby('id', 'ASC');
            /** @var CustomrequestConfigs[] $affectedRes */
            $affectedRes = $this->modx->getIterator($this->classKey, $c);
            foreach ($affectedRes as $affected) {
                $affectedIndex = $affected->get($this->indexKey);
                if ($movingIndex < $targetIndex) {
                    $newIndex = $affectedIndex - 1;
                } else {
                    $newIndex = $affectedIndex + 1;
                }
                $affected->set($this->indexKey, $newIndex);
                $affected->save();
            }
            $res->set($this->indexKey, $targetIndex);
            $res->save();
        }

        if (!$this->hasErrors()) {
            $customrequestCorePath = $this->modx->getOption('customrequest.core_path', null, $this->modx->getOption('core_path') . 'components/customrequest/');
            $customrequest = $this->modx->getService('customrequest', 'CustomRequest', $customrequestCorePath . 'model/customrequest/', array());
            $customrequest->reset();
        }

        return $this->success();
    }
}

return 'CustomrequestConfigsSortmenuindexProcessor';
