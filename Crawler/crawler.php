<?php
include_once 'mz.php';
include_once 'getExcelData.php';

$mz = new Mz('https://www.mh.government.bg/bg/novini/aktualno/');
$url = $mz->getUrls();

//$getData = $mz->printCovid19($url[0]);
$i = 0;
while($getData = $mz->printCovid19($url[$i]) === false){
    $i++;
}

echo "\n\n";
readExelData();
