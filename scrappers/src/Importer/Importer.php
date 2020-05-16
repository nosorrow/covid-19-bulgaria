<?php
/**
 * Author: Plamen Petkov
 * email: plamenorama@gmail.com
 */

namespace Importer;

use Adapter\Database;

class Importer
{
    protected $dbh;
    protected $config;
    public $file;
    public $table;
    public $fields = [];
    public $result = [];

    public function __construct($config)
    {
        $this->config = $config;

        try {
            $dbInstanceName = 'default';

            Database::setInstance($config['host'], $config['user'], $config['password'], $config['dbname'], $dbInstanceName);

            $db = Database::getInstance($dbInstanceName);

            $this->dbh = $db->getpdo();

        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function table($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @return mixed
     */
    public function truncate()
    {
        try {
            return $this->dbh->exec("TRUNCATE {$this->table}");

        } catch (\PDOException $e) {
            echo "Importer::trucate() Error: " . ($e->getMessage());
            exit;
        }
    }

    /**
     * @return \SpreadsheetReader
     * @throws \Exception
     */
    public function read()
    {
        try {
            $reader = new \SpreadsheetReader($this->file);

            return $reader;

        } catch (\Exception $e) {
            die("Importer Error: " . $e->getMessage());
        }
    }


    public function fields()
    {
        if (!$this->fields) {
            die('Database table fields not found!');
        }

        $string['fields'] = implode(', ', $this->fields);

        $string['placeholders'] = rtrim(str_repeat("?, ", count(array_values($this->fields))), ', ');

        return $string;
    }


    public function import()
    {
        $table_info = $this->fields();
        $count_fields = count($this->fields);
        $Reader = $this->read();
        $sizeof = count($Reader);

        $iserted_rows = 0;
        $start = microtime(true);

        try {
            $this->dbh->beginTransaction();

            $stmt = $this->dbh->prepare("DELETE FROM heavy_metal");

            $stmt->execute();

            $sql = "INSERT INTO {$this->table} ({$table_info['fields']}) VALUES ({$table_info['placeholders']})";

            $stmt = $this->dbh->prepare($sql);
            $params = [];

            foreach ($Reader as $row) {

                /*$category = $row[0];
                $steel = $row[1];
                $size = $row[2];
                $qty = $row[3];*/

                for ($i = 0; $i < $count_fields; $i++) {
                    $params[] = $row[$i];
                }

                $stmt->execute($params);

                $iserted_rows++;

                $params = [];
            }

            $this->dbh->commit();

            $this->result['time'] = round(microtime(true) - $start, 2);
            $this->result['read_rows'] = $sizeof;
            $this->result['imported_rows'] = $iserted_rows;
            $this->result['not_imported'] = $sizeof - $iserted_rows;
            $this->result['errors'] = 0;

        } catch (\PDOException $Exception) {
            $error = "FAILED - " . $Exception->getMessage() . "(" . (int)$Exception->getCode() . ") " .  "\n";
            echo $error;
            $this->dbh->rollBack();
            $this->result['errors'] = $error;
            die ('Data base transaction error! Try again later!');
        }

    }

}