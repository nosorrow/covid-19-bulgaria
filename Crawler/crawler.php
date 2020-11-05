<?php
include_once 'mz.php';
include_once 'getExcelData.php';

$mz = new Mz('https://www.mh.government.bg/bg/novini/aktualno/');
$url = $mz->getUrls();
$mz->printCovid19($url[2]);
echo "\n";
readExelData();
//printCovid19($uris[1]);

