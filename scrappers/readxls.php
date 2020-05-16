<?php
error_reporting(E_ALL ^E_WARNING ^E_DEPRECATED);

include_once 'src/vendor/autoload.php';

$file = 'covid-19.xls';

try {
    $reader = new SpreadsheetReader($file);
    foreach ($reader as $k => $row) {
       // var_dump($row);
      // echo $row[0] . " " .$row[1] . " ".$row[2] . " ". $row[3] . " ".$row[4] . " ".$row[5] . "<br>";
        $date = $date = DateTime::createFromFormat('m/d/Y', $row[0]);
        echo $date->format('d-m-Y') . "<br>";
    }


} catch (Exception $e) {
    echo $e->getCode();
}
