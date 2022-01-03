<?php

/**
 * Sortindex configs
 *
 * @package customrequest
 * @subpackage processors
 */
class CustomrequestConfigsSortindexProcessor extends modObjectProcessor
{
    public $classKey = 'CustomrequestConfigs';
    public $languageTopics = array('customrequest:default');
    public $objectType = 'customrequest.repositories';
    public $indexKey = 'menuindex';

    public function process()
    {
        if (!$this->cleanSorting()) {
            return $this->failure();
        }

        $targetId = $this->getProperty('targetId');
        $targetIndex = $this->modx->getObject($this->classKey, $targetId)->get($this->indexKey);

        // Prepare the moving ids
        $movingIds = explode(',', $this->getProperty('movingIds', 0));
        $c = $this->modx->newQuery($this->classKey);
        $c->where(array(
            'id:IN' => $movingIds
        ));
        $c->sortby($this->indexKey, 'ASC');
        $c->sortby('id', 'ASC');
        /** @var xPDOObject[] $movingObjects */
        $movingObjects = $this->modx->getIterator($this->classKey, $c);
        foreach ($movingObjects as $movingObject) {
            $c = $this->modx->newQuery($this->classKey);
            $movingIndex = $movingObject->get($this->indexKey);
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
            /** @var xPDOObject[] $affectedObjects */
            $affectedObjects = $this->modx->getIterator($this->classKey, $c);
            foreach ($affectedObjects as $affectedObject) {
                $affectedIndex = $affectedObject->get($this->indexKey);
                if ($movingIndex < $targetIndex) {
                    $newIndex = $affectedIndex - 1;
                } else {
                    $newIndex = $affectedIndex + 1;
                }
                $affectedObject->set($this->indexKey, $newIndex);
                $affectedObject->save();
            }
            $movingObject->set($this->indexKey, $targetIndex);
            $movingObject->save();
        }

        if (!$this->hasErrors()) {
            $path = $this->modx->getOption('customrequest.core_path', null, $this->modx->getOption('core_path') . 'components/customrequest/');
            $customrequest = $this->modx->getService('customrequest', 'CustomRequest', $path . 'model/customrequest/', array(
                'core_path' => $path
            ));
            $customrequest->reset();
        }

        return $this->success();
    }

    private function cleanSorting()
    {
        $c = $this->modx->newQuery($this->classKey);
        $c->sortby($this->indexKey, 'ASC');
        $c->sortby('id', 'ASC');

        /** @var xPDOObject[] $objects */
        $objects = $this->modx->getIterator($this->classKey, $c);
        if (!$objects) {
            return false;
        }

        $i = 0;
        foreach ($objects as $object) {
            $object->set($this->indexKey, $i);
            $object->save();
            $i++;
        }
        return true;
    }
}

return 'CustomrequestConfigsSortindexProcessor';
