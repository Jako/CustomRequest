<?php
/**
 * Resolve sync tables
 *
 * Only the columns and indexes, that differ from the model, are altered. Altering
 * unchanged columns and indexes can take a long time on tables with a lot of rows.
 *
 * @package customrequest
 * @subpackage build
 *
 * @var array $options
 * @var xPDOTransport $transport
 */

/** @var modX $modx */
$modx = $transport->xpdo;
$modx->log(xPDO::LOG_LEVEL_INFO, 'Running the synctables resolver...');
@set_time_limit(0);

$success = true;

/**
 * Normalize a column type: lower case, single spaces, no display width of integer types
 * (MySQL 8.0.19+ does not report it), integer as int.
 */
$normalizeType = function ($type) {
    $type = strtolower(trim(preg_replace('/\s+/', ' ', (string)$type)));
    $type = preg_replace('/^integer\b/', 'int', $type);
    return preg_replace('/^(tinyint|smallint|mediumint|int|bigint)\(\d+\)/', '$1', $type);
};

/**
 * Normalize a column default: MariaDB reports quoted strings and NULL as string,
 * timestamp functions are reported with and without parentheses.
 */
$normalizeDefault = function ($default, $isMariaDB = false) {
    if ($default === null) {
        return null;
    }
    $default = (string)$default;
    if ($isMariaDB) {
        if (strtoupper($default) === 'NULL') {
            return null;
        }
        if (strlen($default) >= 2 && $default[0] === "'" && substr($default, -1) === "'") {
            $default = str_replace("''", "'", substr($default, 1, -1));
        }
    }
    if (preg_match('/^current_timestamp(\(\))?$/i', $default)) {
        return 'current_timestamp';
    }
    return $default;
};

$sameDefault = function ($expected, $actual) {
    if ($expected === null || $actual === null) {
        return $expected === $actual;
    }
    if (is_numeric($expected) && is_numeric($actual)) {
        return (float)$expected == (float)$actual;
    }
    return (string)$expected === (string)$actual;
};

$updateTableColumns = function ($modx, $table, $isMariaDB) use ($normalizeType, $normalizeDefault, $sameDefault) {
    $tableName = str_replace('`', '', $modx->getTableName($table));
    $c = $modx->prepare('SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT, EXTRA FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = :dbName AND table_name = :tableName');
    $c->execute([':dbName' => $modx->getOption('dbname'), ':tableName' => $tableName]);
    $existingColumns = [];
    foreach ($c->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $existingColumns[$row['COLUMN_NAME']] = $row;
    }

    $m = $modx->getManager();
    $count = 0;
    foreach ($modx->getFieldMeta($table) as $column => $meta) {
        if (!isset($existingColumns[$column])) {
            $m->addField($table, $column);
            $modx->log(xPDO::LOG_LEVEL_INFO, ' -- added column: ' . $column);
            $count++;
            continue;
        }
        $existing = $existingColumns[$column];
        unset($existingColumns[$column]);

        // The expected values follow the column definition of xPDOManager::getColumnDef
        $dbtype = strtolower($meta['dbtype'] ?? '');
        $expectedType = $dbtype;
        if (isset($meta['precision']) && $meta['precision'] !== '') {
            $expectedType .= '(' . $meta['precision'] . ')';
        }
        // Only these attributes are part of the column type, others like ON UPDATE are not
        if (!empty($meta['attributes']) && preg_match_all('/\b(unsigned|zerofill)\b/i', $meta['attributes'], $typeAttributes)) {
            $expectedType .= ' ' . implode(' ', $typeAttributes[1]);
        }
        $differences = [];
        if ($normalizeType($expectedType) !== $normalizeType($existing['COLUMN_TYPE'])) {
            $differences[] = 'type';
        }
        $expectedNull = !isset($meta['null']) || !($meta['null'] === 'false' || empty($meta['null']));
        if ($expectedNull !== ($existing['IS_NULLABLE'] === 'YES')) {
            $differences[] = 'null';
        }
        // Text and blob columns can't have a default value in all supported database versions
        if (!preg_match('/(text|blob|json)$/', $dbtype)) {
            $expectedDefault = isset($meta['default']) && strtoupper((string)$meta['default']) !== 'NULL' ? $normalizeDefault($meta['default']) : null;
            if (!$sameDefault($expectedDefault, $normalizeDefault($existing['COLUMN_DEFAULT'], $isMariaDB))) {
                $differences[] = 'default';
            }
        }
        $pk = $modx->getPK($table);
        $expectedAutoIncrement = (($meta['index'] ?? '') === 'pk' && ($meta['generated'] ?? '') === 'native' && !is_array($pk) && $modx->getPKType($table) === 'integer')
            || stripos($meta['extra'] ?? '', 'auto_increment') !== false;
        if ($expectedAutoIncrement !== (stripos($existing['EXTRA'], 'auto_increment') !== false)) {
            $differences[] = 'auto_increment';
        }
        if ($differences) {
            $m->alterField($table, $column);
            $modx->log(xPDO::LOG_LEVEL_INFO, ' -- altered column: ' . $column . ' (' . implode(', ', $differences) . ')');
            $count++;
        }
    }

    foreach (array_keys($existingColumns) as $column) {
        $m->removeField($table, $column);
        $modx->log(xPDO::LOG_LEVEL_INFO, ' -- removed column: ' . $column);
        $count++;
    }
    return $count;
};

$updateTableIndexes = function ($modx, $table) {
    $tableName = str_replace('`', '', $modx->getTableName($table));
    $c = $modx->prepare("SELECT INDEX_NAME, NON_UNIQUE, COLUMN_NAME, SUB_PART, INDEX_TYPE FROM INFORMATION_SCHEMA.STATISTICS WHERE table_schema = :dbName AND table_name = :tableName AND INDEX_NAME != 'PRIMARY' ORDER BY INDEX_NAME, SEQ_IN_INDEX");
    $c->execute([':dbName' => $modx->getOption('dbname'), ':tableName' => $tableName]);
    $existingIndexes = [];
    foreach ($c->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $name = $row['INDEX_NAME'];
        if (!isset($existingIndexes[$name])) {
            $existingIndexes[$name] = [
                'unique' => !$row['NON_UNIQUE'],
                'type' => strtoupper($row['INDEX_TYPE']),
                'columns' => [],
            ];
        }
        $existingIndexes[$name]['columns'][] = $row['COLUMN_NAME'] . ($row['SUB_PART'] ? '(' . $row['SUB_PART'] . ')' : '');
    }

    $m = $modx->getManager();
    $count = 0;
    foreach ($modx->getIndexMeta($table) as $index => $meta) {
        if ($index === 'PRIMARY' || !empty($meta['primary'])) {
            continue;
        }
        $columns = [];
        foreach ($meta['columns'] ?? [] as $column => $columnMeta) {
            $columns[] = $column . (!empty($columnMeta['length']) ? '(' . $columnMeta['length'] . ')' : '');
        }
        $expected = [
            'unique' => !empty($meta['unique']),
            'type' => strtoupper(!empty($meta['type']) ? $meta['type'] : 'BTREE'),
            'columns' => $columns,
        ];
        if (isset($existingIndexes[$index])) {
            $existing = $existingIndexes[$index];
            unset($existingIndexes[$index]);
            if ($existing == $expected) {
                continue;
            }
            $m->removeIndex($table, $index);
            $m->addIndex($table, $index);
            $modx->log(xPDO::LOG_LEVEL_INFO, ' -- altered index: ' . $index);
        } else {
            $m->addIndex($table, $index);
            $modx->log(xPDO::LOG_LEVEL_INFO, ' -- added index: ' . $index);
        }
        $count++;
    }

    foreach (array_keys($existingIndexes) as $index) {
        $m->removeIndex($table, $index);
        $modx->log(xPDO::LOG_LEVEL_INFO, ' -- removed index: ' . $index);
        $count++;
    }
    return $count;
};

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        $tables = [
            "CustomrequestConfigs"
        ];

        $modelPath = $modx->getOption('customrequest.core_path', null, $modx->getOption('core_path') . 'components/customrequest/') . 'model/';
        $modx->addPackage('customrequest', $modelPath);

        $version = $modx->query('SELECT VERSION()');
        $isMariaDB = $version && stripos((string)$version->fetchColumn(), 'mariadb') !== false;

        $columnCount = 0;
        $indexCount = 0;
        foreach ($tables as $table) {
            $modx->log(xPDO::LOG_LEVEL_INFO, 'Syncing table: ' . $table);
            $columnCount += $updateTableColumns($modx, $table, $isMariaDB);
            $indexCount += $updateTableIndexes($modx, $table);
        }
        $modx->log(xPDO::LOG_LEVEL_INFO, 'Synced ' . count($tables) . ' tables: ' . $columnCount . ' column and ' . $indexCount . ' index changes.');
        break;
}
return $success;
