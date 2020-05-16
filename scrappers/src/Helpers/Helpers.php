<?php
use Importer\Importer;

/**
 * @param array $config
 * @param $file
 * @param $dbtable
 * @param array $fields
 * @return array
 */
function xls2mysql(array $config, $file, $dbtable , array $fields){
    $importer = new Importer($config);
    $importer->file = $file;
    $importer->table = $dbtable;
    $importer->fields = $fields;
    $importer->import();
    return $importer->result;
}